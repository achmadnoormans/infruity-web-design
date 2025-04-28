<?php

namespace Modules\Permohonan\Http\Controllers;

use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Modules\Permohonan\Entities\Layanan;
use Modules\Permohonan\Entities\LayananDocument;
use Modules\Permohonan\Entities\LayananForm;
use Modules\Permohonan\Entities\ListKolektif;
use Modules\Permohonan\Entities\Permohonan;
use Modules\Permohonan\Entities\PermohonanArsip;
use Modules\Permohonan\Entities\PermohonanBap;
use Modules\Permohonan\Entities\PermohonanDocument;
use Modules\Permohonan\Entities\PermohonanHistory;
use Modules\Permohonan\Entities\PermohonanSurat;
use Modules\Permohonan\Entities\StatusDokumen;
use Modules\Permohonan\Http\Requests\PermohonanRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Session;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;

class PermohonanController extends Controller
{
    /**
     * Trait.
     * @return Response
     */

    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $permohonan = Permohonan::select([
            't_permohonan.id',
            't_permohonan.no_permohonan',
            't_permohonan.tanggal_pengajuan',
            't_permohonan.nama_pemohon',
            't_permohonan.alamat_persil',
            't_permohonan.id_layanan',
            'm_layanan.nm_layanan',
            'posisi_berkas.posisi',
            'role.nm_role',
            'm_status.nama_status',
            'm_status.icon',
            'm_status.class_color'
        ])
            ->distinct()
            ->leftJoin('m_layanan', 't_permohonan.id_layanan', '=', 'm_layanan.id_layanan')
            ->leftJoin('posisi_berkas', function ($join) {
                $join->on('posisi_berkas.id_status', '=', 't_permohonan.id_status')
                    ->on('posisi_berkas.id_layanan', '=', 't_permohonan.id_layanan');
            })
            ->leftJoin('m_status', 't_permohonan.id_status', '=', 'm_status.id_status')
            ->leftJoin('role', 'posisi_berkas.posisi', '=', 'role.id_role')
            ->leftJoin('bidang_layanan', 't_permohonan.id_layanan', '=', 'bidang_layanan.id_layanan')
            ->where('bidang_layanan.bidang', Auth::user()->bidang);

        if (Session('role')['id_role'] == 99) {
            return redirect('list-permohonan')->with('success', 'List Permohonan Saya');
        } else {
            $permohonan = $permohonan->where('t_permohonan.id_status', '<', value: 11)
                ->orderBy('id', 'ASC');
        }

        if ($request->id_status) {
            $permohonan = $permohonan->where('t_permohonan.id_status', $request->id_status);
        }

        if ($request->id_layanan) {
            $permohonan = $permohonan->where('t_permohonan.id_layanan', $request->id_layanan);
        }

        if ($request->id_permohonan) {
            $permohonan = $permohonan->where('t_permohonan.id', $request->id_permohonan);
        }

        if (isset($request->nm_pemohon)) {
            $permohonan = $permohonan->where('t_permohonan.nama_pemohon', 'LIKE', "%$request->nm_pemohon%");
        }

        if (isset($request->alamat_persil)) {
            $permohonan = $permohonan->where('t_permohonan.alamat_persil', 'LIKE', "%$request->alamat_persil%");
        }

        if (Session('role')['id_role'] == 99) {
            $permohonan->where('id_user', Auth::user()->id_user);
        }

        if (in_array(Auth::user()->bidang, ['P2BMD'])) {
            $permohonan = $permohonan->whereIn('t_permohonan.id_layanan', [6, 7]);
        }

        $data["data"] = $permohonan->paginate(10);
        $data["layanan"] = Layanan::all();
        $statusDokumen = StatusDokumen::all();
        foreach ($statusDokumen as $key => $value) {
            $data['status'][$value->id_status] = $value;
        }
        $data["filter"] = [
            'id_status' => $request->id_status ?? '',
            'id_layanan' => $request->id_layanan ?? '',
            'permohonan' => isset($request->id_permohonan) ? Permohonan::findOrFail($request->id_permohonan) : '',
            'nm_pemohon' => $request->nm_pemohon,
            'alamat_persil' => $request->alamat_persil,
        ];
        return view('permohonan::index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $data["layanan"] = Layanan::all();
        $data["document"] = [];
        if (isset($request->tipe)) {
            $data["document"] = LayananDocument::where('id_layanan', $request->tipe)->get();
            $data["form"] = LayananForm::where('id_layanan', $request->tipe)->get();
        }
        if (isset($request->tipe)) {
            $data["selectedLayanan"] = Layanan::findOrFail($request->tipe);
        }
        $data["tipe"] = $request->tipe ?? '';
        $data["jenisIklan"] = [
            'KORAN' => 'KORAN',
            'DILOKASI' => 'DILOKASI'
        ];
        return view('permohonan::create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(PermohonanRequest $request)
    {
        try {
            DB::beginTransaction();
            $permohonan = new Permohonan();
            $permohonan->id_layanan = $request->tipe;
            $permohonan->no_permohonan = "BPKAD/SUKET/" . date("ymd") . '/' . substr(crc32(uniqid()), -4);
            $permohonan->nama_pemohon = $request->nama_pemohon ?? '';
            $permohonan->alamat_pemohon = $request->alamat_pemohon ?? '';
            $permohonan->telepon_pemohon = $request->telepon_pemohon ?? '';
            $permohonan->nama_pemegang_ipt = $request->nama_pemegang_ipt ?? '';
            $permohonan->no_ipt = $request->no_ipt ?? '';
            $permohonan->tanggal_ipt = $request->tanggal_ipt ?? '';
            $permohonan->alamat_persil = $request->alamat_persil ?? '';
            $permohonan->nomor_kehilangan_dari_kepolisian = $request->nomor_kehilangan_dari_kepolisian ?? '';
            $permohonan->tanggal_pengajuan = $request->tanggal_pengajuan ?? date('Y-m-d');
            $permohonan->pekerjaan_pemohon = $request->pekerjaan_pemohon ?? '';
            $permohonan->kelurahan = $request->kelurahan ?? '';
            $permohonan->kecamatan = $request->kecamatan ?? '';
            $permohonan->kota = $request->kota ?? '';
            if (in_array($request->tipe, [2, 3])) {
                $permohonan->jenis_iklan = $request->jenis_iklan ?? 'KORAN';
            }
            $permohonan->id_status = 100;
            $permohonan->id_user = Auth::user()->id_user;

            $permohonan->save();

            $dokumen = LayananDocument::where('id_layanan', $permohonan->id_layanan)->get();
            foreach ($dokumen as $key => $value) {
                $namaDocument = change_form($value->nama_document);
                if (isset($request->$namaDocument) and $request->file($namaDocument) != null) {
                    $doc = $request->file($namaDocument);
                    $path_doc = $doc->store($namaDocument);
                    $permohonanDocument = new PermohonanDocument();
                    $permohonanDocument->id_permohonan = $permohonan->id;
                    $permohonanDocument->file = $path_doc;
                    $permohonanDocument->save();
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Pembuatan Permohonan gagal' . $e->getMessage());
        }

        return redirect('permohonan/' . $permohonan->id . '/detail')->with('success', 'Pembuatan Permohonan berhasil');
    }

    /**
     * Upload File the specified resource.
     * @param int $id
     * @return Response
     */
    public function upload_file(Request $request)
    {
        // dd($request->all());
        try {
            $permohonan = Permohonan::findOrFail($request->id_permohonan);
            $dokumen = LayananDocument::where('id_layanan', $permohonan->id_layanan)->get();
            foreach ($dokumen as $key => $value) {
                $namaDocument = change_form($value->nama_document);
                if (isset($request->$namaDocument) and $request->file($namaDocument) != null) {
                    $validator = Validator::make($request->all(), [
                        $namaDocument => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
                    ]);
                    if ($validator->fails()) {
                        return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
                    }
                    $doc = $request->file($namaDocument);
                    $path_doc = $doc->store($namaDocument);
                    $permohonanDocument = new PermohonanDocument();
                    $permohonanDocument->id_permohonan = $permohonan->id;
                    $permohonanDocument->file = $path_doc;
                    $permohonanDocument->save();
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Upload Data ' . $e->getMessage());
        }
        return redirect()->back()->with('success', 'Berhasil Upload Data');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $data["data"] = Permohonan::with('layanan')->leftJoin('posisi_berkas', function ($join) {
            $join->on('posisi_berkas.id_status', '=', 't_permohonan.id_status')
                ->on('posisi_berkas.id_layanan', '=', 't_permohonan.id_layanan');
        })
            ->leftJoin('role', 'posisi_berkas.posisi', '=', 'role.id_role')
            ->select('t_permohonan.*', 'posisi_berkas.posisi as posisi_berkas', 'role.nm_role as nm_role')
            ->findOrFail($id);
        $this->authorize('view', $data['data']);
        $document = PermohonanDocument::where('id_permohonan', $id)->get();
        $arrDoc = [];
        foreach ($document as $key => $value) {
            $filename = explode("/", $value->file);
            $arrDoc[$filename[0]] = $value->file;
        }
        $data["form"] = LayananForm::where('id_layanan', $data["data"]->id_layanan)->get();
        $data["document"] = LayananDocument::where('id_layanan', $data["data"]->id_layanan)->get();
        $data["dataDocument"] = $arrDoc;
        $data["history"] = PermohonanHistory::getRoleUser($id, NULL);
        if ($data["data"]->id_status == 11 && isset($data["data"]->id_surat)) {
            $data["surat"] = PermohonanSurat::findOrFail($data["data"]->id_surat);
        }
        // dd($data);
        return view('permohonan::show', $data);
    }

    /**
     * Submit Data the specified resource.
     * @param int $id
     * @return Response
     */
    public function submit_data($id)
    {
        try {
            $status = false;
            $permohonan = Permohonan::findOrFail($id);
            $dokumen = LayananDocument::where('id_layanan', $permohonan->id_layanan)->get();
            $permohonanDokumen = PermohonanDocument::where('id_permohonan', $id)->get();
            $dokumen = $dokumen->where('status', 'required');
            $arrDoc = [];
            $arrDocL = [];
            foreach ($permohonanDokumen as $key => $value) {
                $filename = explode("/", $value->file);
                $arrDoc[$filename[0]] = $filename[0];
            }
            foreach ($dokumen as $key => $value) {
                $arrDocL[change_form($value->nama_document)] = change_form($value->nama_document);
            }
            if (empty(array_diff_key($arrDocL, $arrDoc))) {
                Permohonan::where("id", $permohonan->id)->update([
                    'id_status' => 1,
                    'tanggal_pengajuan' => date('Y-m-d'),
                ]);

                $history = new PermohonanHistory();
                $history->id_permohonan = $permohonan->id;
                $history->id_status = 1;
                $history->nm_status = 'SUBMIT';
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = date('Y-m-d');
                $history->keterangan = 'Berhasil Upload Semua Berkas';
                $history->save();

                $status = true;
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Upload Data ' . $e->getMessage());
        }

        if ($status) {
            return redirect()->back()->with('success', 'Berhasil Upload Data');
        } else {
            return redirect()->back()->with('error', 'Data Belum Lengkap');
        }
    }

    /**
     * History the specified resource.
     * @param int $id
     * @return Response
     */
    public function history($id)
    {
        $data["data"] = Permohonan::with('layanan')->findOrFail($id);
        $this->authorize('view', $data['data']);
        $data["history"] = PermohonanHistory::with('user')->where('id_permohonan', $id)->where('type', NULL)->get();

        // dd($data);
        return view('permohonan::history', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data["data"] = Permohonan::findOrFail($id);
        $document = PermohonanDocument::where('id_permohonan', $id)->get();
        $arrDoc = [];
        foreach ($document as $key => $value) {
            $filename = explode("/", $value->file);
            $arrDoc[$filename[0]] = $value->file;
        }
        $data["dataDocument"] = $arrDoc;
        $data["document"] = LayananDocument::where('id_layanan', $data["data"]->id_layanan)->get();
        $data["form"] = LayananForm::where('id_layanan', $data["data"]->id_layanan)->get();
        // dd($data);
        return view('permohonan::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(PermohonanRequest $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $permohonan = Permohonan::findOrFail($id);
            $permohonan->nama_pemohon = $request->nama_pemohon ?? '';
            $permohonan->alamat_pemohon = $request->alamat_pemohon ?? '';
            $permohonan->telepon_pemohon = $request->telepon_pemohon ?? '';
            $permohonan->nama_pemegang_ipt = $request->nama_pemegang_ipt ?? '';
            $permohonan->no_ipt = $request->no_ipt ?? '';
            $permohonan->tanggal_ipt = $request->tanggal_ipt ?? '';
            $permohonan->alamat_persil = $request->alamat_persil ?? '';
            $permohonan->nomor_kehilangan_dari_kepolisian = $request->nomor_kehilangan_dari_kepolisian ?? '';
            $permohonan->tanggal_pengajuan = $request->tanggal_pengajuan ?? date('Y-m-d');
            $permohonan->pekerjaan_pemohon = $request->pekerjaan_pemohon ?? '';
            $permohonan->kelurahan = $request->kelurahan ?? '';
            $permohonan->kecamatan = $request->kecamatan ?? '';
            $permohonan->kota = $request->kota ?? '';
            if (in_array($request->tipe, [2, 3])) {
                $permohonan->jenis_iklan = $request->jenis_iklan ?? 'KORAN';
            }
            $permohonan->id_user = Auth::user()->id_user;
            $permohonan->save();

            // Cek dokumen
            // $cek = PermohonanDocument::where('id_permohonan', $id)->get();
            // if (isset($cek) && count($cek) > 0) {
            //     PermohonanDocument::where('id_permohonan', $id)->delete();
            // }
            $dokumen = LayananDocument::where('id_layanan', $permohonan->id_layanan)->get();
            foreach ($dokumen as $key => $value) {
                $namaDocument = change_form($value->nama_document);
                if (isset($request->$namaDocument) and $request->file($namaDocument) != null) {
                    $doc = $request->file($namaDocument);
                    $path_doc = $doc->store($namaDocument);
                    $permohonanDocument = new PermohonanDocument();
                    $permohonanDocument->id_permohonan = $permohonan->id;
                    $permohonanDocument->file = $path_doc;
                    $permohonanDocument->save();
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Update Permohonan gagal' . $e->getMessage());
        }

        return redirect('permohonan')->with('success', 'Update Permohonan berhasil');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $permohonan = Permohonan::findOrFail($id);
            $permohonan->update(['deleted_at' => now()]);
            $document = PermohonanDocument::where('id_permohonan', $id)->update(['deleted_at' => now()]);
            $document = PermohonanHistory::where('id_permohonan', $id)->update(['deleted_at' => now()]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Permohonan Gagal Dihapus ' . $e->getMessage());
        }
        return redirect()->back()->with('success', 'Data Permohonan dihapus');
    }

    public function get_doc(Request $request)
    {
        $path = $request->pdf;
        if (Storage::exists($path)) {
            return Storage::response($path);
        }
    }

    /**
     * Cetak Permohnan the specified resource.
     * @param int $id
     * @return Response
     */
    public function cetak($id)
    {
        $data["data"] = Permohonan::with('layanan')->findOrFail($id);
        $this->authorize('view', $data['data']);
        $document = PermohonanDocument::where('id_permohonan', $id)->get();
        $arrDoc = [];
        foreach ($document as $key => $value) {
            $filename = explode("/", $value->file);
            $arrDoc[$filename[0]] = $value->file;
        }
        $data["form"] = LayananForm::where('id_layanan', $data["data"]->id_layanan)->get();
        $data["document"] = LayananDocument::where('id_layanan', $data["data"]->id_layanan)->get();
        $data["dataDocument"] = $arrDoc;

        $pdf = \PDF::loadview("permohonan::cetak", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    /**
     * Cetak Formulir the specified resource.
     * @param int $id
     * @return Response
     */
    public function cetak_formulir($id)
    {
        $data["data"] = Permohonan::with('layanan')->findOrFail($id);
        $this->authorize('view', $data['data']);
        $document = PermohonanDocument::where('id_permohonan', $id)->get();
        $arrDoc = [];
        foreach ($document as $key => $value) {
            $filename = explode("/", $value->file);
            $arrDoc[$filename[0]] = $value->file;
        }
        $data["form"] = LayananForm::where('id_layanan', $data["data"]->id_layanan)->get();
        $data["document"] = LayananDocument::where('id_layanan', $data["data"]->id_layanan)->get();
        $data["dataDocument"] = $arrDoc;

        switch ($data["data"]->id_layanan) {
            case 1:
                $pdf = \PDF::loadview("permohonan::cetak.formulir-fotocopy-ktp", $data)
                    ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');
                break;
            case 2:
            case 4:
                $pdf = \PDF::loadview("permohonan::cetak.formulir-rekom-iklan", $data)
                    ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');
                break;
            case 3:
            case 5:
                $pdf = \PDF::loadview("permohonan::cetak.formulir-balik-nama", $data)
                    ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');
                break;
            case 7:
                $pdf = \PDF::loadview("permohonan::cetak.formulir-permohonan-status-tanah", $data)
                    ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');
                break;
            default:
                $pdf = \PDF::loadview("permohonan::cetak-formulir", $data)
                    ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');
                break;
        }

        return $pdf->stream();
    }

    /**
     * Verifikasi the specified resource.
     * @param int $id
     * @return Response
     */
    public function verifikasi($id)
    {
        $data["data"] = Permohonan::with('layanan')->leftJoin('posisi_berkas', function ($join) {
            $join->on('posisi_berkas.id_status', '=', 't_permohonan.id_status')
                ->on('posisi_berkas.id_layanan', '=', 't_permohonan.id_layanan');
        })
            ->leftJoin('role', 'posisi_berkas.posisi', '=', 'role.id_role')
            ->select('t_permohonan.*', 'posisi_berkas.posisi as posisi_berkas', 'role.nm_role as nm_role')
            ->findOrFail($id);
        // dd($data);
        $document = PermohonanDocument::where('id_permohonan', $id)->get();
        $arrDoc = [];
        foreach ($document as $key => $value) {
            $filename = explode("/", $value->file);
            $arrDoc[$filename[0]] = $value->file;
        }
        $data["form"] = LayananForm::where('id_layanan', $data["data"]->id_layanan)->get();
        $data["document"] = LayananDocument::where('id_layanan', $data["data"]->id_layanan)->get();
        $data["dataDocument"] = $arrDoc;
        $data["arsip"] = PermohonanArsip::where('id_permohonan', $id)->orderBy('id', 'DESC')->first();
        $data["bap"] = PermohonanBap::where('id_permohonan', $id)->orderBy('id', 'DESC')->first();

        if (in_array($data['data']->id_layanan, [4, 5])) {
            $kolektif = Permohonan::with('layanan')->whereIn('id_layanan', [4, 5])->where('id_surat', NULL);
            $data["kolektif"] = $kolektif->get();
            $data["totalKolektif"] = $kolektif->count();
            $dataKolektif = ListKolektif::where('is_surat', NULL)->orWhere('is_surat', FALSE)->get();
            $data["totalListKolektif"] = $dataKolektif->count();
            $data["listKolektif"] = array_column($dataKolektif->toArray(), 'id_permohonan');
        }

        $data['petugasSurvey'] = User::getDataPetugasSurvey();

        $data['status'] = Permohonan::getStatusVerifikasi($data['data']->id_layanan, $data['data']->id_status);
        $data["history"] = PermohonanHistory::getRoleUser($id, NULL);

        if (isset($data['data']->id_surat)) {
            $data["surat"] = PermohonanSurat::findOrFail($data['data']->id_surat);
        } else {
            $data["surat"] = null;
        }
        // dd($data);
        return view('permohonan::verifikasi', $data);
    }

    /**
     * Verifikasi the specified resource.
     * @param int $id
     * @return Response
     */
    public function do_verifikasi(Request $request)
    {
        try {
            $permohonan = Permohonan::findOrFail($request->id_permohonan);
            $permohonanStatus = $permohonan->id_status;
            DB::beginTransaction();
            if ($permohonan->id_layanan == 7) {
                $id_status = $request->action;
                $status = StatusDokumen::findOrFail($id_status);
                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $id_status == 1 ? 99 : $id_status;
                $history->nm_status = $id_status == 1 ? 'Data Dikembalikan' : $status->nama_status;
                $history->nm_status = $status->nama_status;
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl;
                $history->keterangan = $request->keterangan;
                $history->save();

                if ($id_status == 1) {
                    $isLengkap = null;
                } else {
                    $isLengkap = 1;
                }

                Permohonan::where("id", $request->id_permohonan)->update([
                    'id_status' => $id_status,
                    'is_lengkap' => $isLengkap,
                ]);
            } else {
                if ($request->action > $permohonanStatus) {
                    $id_status = $request->action;
                    $status = StatusDokumen::findOrFail($id_status);
                    $history = new PermohonanHistory();
                    $history->id_permohonan = $request->id_permohonan;
                    $history->id_status = $id_status;
                    $history->nm_status = $status->nama_status;
                    $history->id_verifikator = Auth::user()->id_user;
                    $history->nama_verifikator = Auth::user()->nm_user;
                    $history->tgl_status = $request->tgl;
                    $history->keterangan = $request->keterangan;
                    $history->save();

                    Permohonan::where("id", $request->id_permohonan)->update([
                        'id_status' => $id_status,
                    ]);
                } else {
                    $id_status = $request->action;
                    $status = StatusDokumen::findOrFail(99);
                    $history = new PermohonanHistory();
                    $history->id_permohonan = $request->id_permohonan;
                    $history->id_status = $status->id_status;
                    $history->nm_status = $status->nama_status;
                    $history->id_verifikator = Auth::user()->id_user;
                    $history->nama_verifikator = Auth::user()->nm_user;
                    $history->tgl_status = $request->tgl;
                    $history->keterangan = $request->keterangan;
                    $history->save();

                    Permohonan::where("id", $request->id_permohonan)->update([
                        'id_status' => $id_status,
                    ]);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal memverifikasi ' . $e->getMessage());
        }
        // dd(Session('role')['id_role']);
        switch (Session('role')['id_role']) {
            case 6:
                return redirect('permohonan-verifikasi-sekretaris')->with('success', 'Berhasil Memverifikasi Data dan Melanjutkan ke Proses Selanjutnya');
                break;

            default:
                return redirect('dashboard')->with('success', 'Berhasil Memverifikasi Data dan Melanjutkan ke Proses Selanjutnya');
                break;
        }
    }

    /**
     * submit the specified resource.
     * @param int $id
     * @return Response
     */
    public function submit(Request $request)
    {
        try {
            $permohonan = Permohonan::findOrFail($request->id_permohonan);
            $permohonanDokumen = PermohonanDocument::where('id_permohonan', $request->id_permohonan)->get();
            $dokumen = LayananDocument::where('id_layanan', $permohonan->id_layanan)->where('status', 'required')->get();
            if (count($dokumen) <= count($permohonanDokumen)) {
                Permohonan::where("id", $permohonan->id)->update([
                    'id_status' => 1,
                ]);

                $history = new PermohonanHistory();
                $history->id_permohonan = $permohonan->id;
                $history->id_status = 1;
                $history->nm_status = 'SUBMIT';
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = date('Y-m-d');
                $history->keterangan = 'Berhasil Submit Semua Berkas';
                $history->save();
            }

            // dd(count($dokumen),$dokumen,count($permohonanDokumen), $permohonanDokumen);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Upload Data ' . $e->getMessage());
        }
        return redirect()->back()->with('success', 'Berhasil Upload Data');
    }

    /**
     * Upload Formulir the specified resource.
     * @param int $id
     * @return Response
     */
    public function upload_formulir(Request $request)
    {
        // dd($request->all());
        try {
            $permohonan = Permohonan::findOrFail($request->id_permohonan);
            DB::beginTransaction();

            if (isset($request->formulir) and $request->file('formulir') != null) {
                $doc = $request->file('formulir');
                $path_doc = $doc->store('formulir');
                $permohonanDocument = new PermohonanDocument();
                $permohonanDocument->id_permohonan = $permohonan->id;
                $permohonanDocument->file = $path_doc;
                $permohonanDocument->save();

                Permohonan::where("id", $permohonan->id)->update([
                    'id_status' => 1,
                ]);

                $history = new PermohonanHistory();
                $history->id_permohonan = $permohonan->id;
                $history->id_status = 1;
                $history->nm_status = 'SUBMIT';
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = date('Y-m-d');
                $history->keterangan = 'Berhasil Upload Formulir';
                $history->save();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Upload Formulir ' . $e->getMessage());
        }
        return redirect()->back()->with('success', 'Berhasil Upload Formulir');
    }

    /**
     * Upload BAP the specified resource.
     * @param int $id
     * @return Response
     */
    public function upload_bap(Request $request)
    {
        try {
            $permohonan = Permohonan::findOrFail($request->id_permohonan);
            $id_status = $permohonan->id_status;
            DB::beginTransaction();
            if ($request->action == '3') {
                $array = [
                    'peruntukan' => 'required',
                    'penggunaan' => 'required',
                    'no_ipt' => 'required',
                    'tanggal_ipt' => 'required',
                    'luas' => 'required',
                    'file_bap' => 'required|mimes:pdf,jpg,jpeg|max:2048',
                ];
                if ($permohonan->id_layanan == 7) {
                    $array = [
                        'file_bap' => 'required|mimes:pdf,jpg,jpeg|max:2048',
                    ];
                }
                $validator = Validator::make($request->all(), $array);

                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
                $id_status = $id_status + 1;
                if (in_array($permohonan->id_layanan, [7])) {
                    $id_status = 3;
                }
                $status = StatusDokumen::findOrFail($id_status);
                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $id_status;
                $history->nm_status = $status->nama_status;
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl;
                $history->keterangan = $request->keterangan;
                $history->save();

                Permohonan::where("id", $request->id_permohonan)->update([
                    'id_status' => $id_status,
                ]);

                if (isset($request->file_bap) and $request->file('file_bap') != null) {

                    PermohonanBap::where('id_permohonan', $request->id_permohonan)->update(['deleted_at' => now()]);

                    $doc = $request->file('file_bap');
                    $path_doc = $doc->store('file_bap');
                    $permohonanBap = new PermohonanBap();
                    $permohonanBap->id_permohonan = $request->id_permohonan;
                    $permohonanBap->file = $path_doc;
                    $permohonanBap->peruntukan = $request->peruntukan;
                    $permohonanBap->penggunaan = $request->penggunaan;
                    $permohonanBap->no_ipt = $request->no_ipt;
                    $permohonanBap->tanggal_ipt = $request->tanggal_ipt;
                    $permohonanBap->luas = $request->luas;
                    $permohonanBap->save();
                }
            } else {
                $id_status = $request->action;
                $status = StatusDokumen::findOrFail(99);
                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $status->id_status;
                $history->nm_status = $status->nama_status;
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl;
                $history->keterangan = $request->keterangan;
                $history->save();

                Permohonan::where("id", $request->id_permohonan)->update([
                    'id_status' => $id_status,
                    // 'is_lengkap' => 0,
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Upload BAP ' . $e->getMessage());
        }
        if ($request->action == '3') {
            return redirect()->back()->with('success', 'Berhasil Memverifikasi Data dan melanjutkan ke Proses Selanjutnya');
        } else {
            return redirect()->back()->with('error', 'Berhasil mengembalikan ke proses sebelumnya');
        }
    }

    /**
     * Verifikasi Arsip the specified resource.
     * @param int $id
     * @return Response
     */
    public function verifikasi_arsip(Request $request)
    {
        // dd($request->all());
        try {
            $permohonan = Permohonan::findOrFail($request->id_permohonan);
            $id_status = $permohonan->id_status;
            DB::beginTransaction();
            if ($request->action == 2) {
                $validator = Validator::make($request->all(), [
                    'tanggal_ipt' => 'required',
                    'alamat_persil' => 'required',
                    'nama_pemegang_ijin' => 'required',
                    'no_persil' => 'required',
                    'action' => 'required',
                    'file_ipt' => 'required|mimes:pdf,jpg,jpeg|max:2048',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
                $id_status = $id_status + 1;
                $status = StatusDokumen::findOrFail($id_status);
                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $id_status;
                $history->nm_status = $status->nama_status;
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl;
                $history->keterangan = $request->keterangan_history;
                $history->save();

                Permohonan::where("id", $request->id_permohonan)->update([
                    'id_status' => $id_status,
                ]);

                PermohonanArsip::where('id_permohonan', $request->id_permohonan)->update(['deleted_at' => now()]);

                if (isset($request->file_ipt) and $request->file('file_ipt') != null) {
                    $doc = $request->file('file_ipt');
                    $path_doc = $doc->store('file_ipt');
                    $permohonanArsip = new PermohonanArsip();
                    $permohonanArsip->id_permohonan = $request->id_permohonan;
                    $permohonanArsip->no_persil = $request->no_persil;
                    $permohonanArsip->alamat_persil = $request->alamat_persil;
                    $permohonanArsip->nama_pemegang_ijin = $request->nama_pemegang_ijin;
                    $permohonanArsip->tanggal_ipt = $request->tanggal_ipt;
                    $permohonanArsip->keterangan = $request->keterangan;
                    $permohonanArsip->file = $path_doc;
                    $permohonanArsip->save();
                }
            } else {
                $id_status = $id_status - 1;
                if ($id_status == 0) {
                    $id_status = 99;
                }
                $status = StatusDokumen::findOrFail(99);
                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $status->id_status;
                $history->nm_status = $status->nama_status;
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl;
                $history->keterangan = $request->keterangan;
                $history->save();

                Permohonan::where("id", $request->id_permohonan)->update([
                    'id_status' => $id_status,
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Upload BAP ' . $e->getMessage());
        }
        if ($request->action == '2') {
            return redirect('permohonan-submit')->with('success', 'Berhasil Memverifikasi Dan Membuat Keterangan Arsip');
        } else {
            return redirect()->back()->with('error', 'Verifikasi Data Ditolak');
        }
    }

    /**
     * Verifikasi Berkas the specified resource.
     * @param int $id
     * @return Response
     */
    public function verifikasi_berkas(Request $request)
    {
        // dd($request->all());
        try {
            $permohonan = Permohonan::findOrFail($request->id_permohonan);
            $id_status = $permohonan->id_status;
            DB::beginTransaction();
            if ($request->action == 2) {
                $history = new PermohonanHistory();
                $status = StatusDokumen::findOrFail(1);
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $id_status;
                $history->nm_status = 'PENERIMA BERKAS';
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl;
                $history->keterangan = $request->keterangan;
                $history->save();

                Permohonan::where("id", $request->id_permohonan)->update([
                    'is_lengkap' => 1,
                ]);

                if ($permohonan->id_layanan == 7) {
                    Permohonan::where("id", $request->id_permohonan)->update([
                        'id_status' => 2,
                    ]);
                }
            } else {
                $id_status = $id_status - 1;
                if ($id_status == 0) {
                    $id_status = 99;
                }
                $status = StatusDokumen::findOrFail(99);
                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $status->id_status;
                $history->nm_status = $status->nama_status;
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl;
                $history->keterangan = $request->keterangan;
                $history->save();

                Permohonan::where("id", $request->id_permohonan)->update([
                    'id_status' => $id_status,
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Cek Berkas ' . $e->getMessage());
        }
        if ($request->action == '2') {
            return redirect()->back()->with('success', 'Berhasil Memverifikasi Berkas');
        } else {
            return redirect()->back()->with('error', 'Verifikasi Data Ditolak');
        }
    }

    /**
     * Upload Surat the specified resource.
     * @param int $id
     * @return Response
     */
    public function verifikasi_surat(Request $request)
    {
        try {
            $permohonan = Permohonan::findOrFail($request->id_permohonan);
            $permohonanStatus = $permohonan->id_status;
            DB::beginTransaction();
            if ($permohonan->id_layanan == 7) {
                $validator = Validator::make($request->all(), [
                    'petugas_survey' => 'required|exists:users,id_user',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
                $id_status = $request->action;
                $status = StatusDokumen::findOrFail($id_status);
                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $id_status == 1 ? 99 : $id_status;
                $history->nm_status = $id_status == 1 ? 'Data Dikembalikan' : $status->nama_status;
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl;
                $history->keterangan = $request->keterangan;
                $history->save();

                if ($id_status == 1) {
                    $petugasSurvey = null;
                    $isLengkap = null;
                } else {
                    $petugasSurvey = $request->petugas_survey;
                    $isLengkap = 1;
                }

                Permohonan::where("id", $request->id_permohonan)->update([
                    'id_status' => $id_status,
                    'id_petugas_survey' => $petugasSurvey,
                    'is_lengkap' => $isLengkap,
                ]);
            } else {
                if ($request->action > $permohonanStatus) {
                    $id_status = $request->action;
                    $status = StatusDokumen::findOrFail($id_status);
                    $history = new PermohonanHistory();
                    $history->id_permohonan = $request->id_permohonan;
                    $history->id_status = $id_status;
                    $history->nm_status = $status->nama_status;
                    $history->id_verifikator = Auth::user()->id_user;
                    $history->nama_verifikator = Auth::user()->nm_user;
                    $history->tgl_status = $request->tgl;
                    $history->keterangan = $request->keterangan;
                    $history->save();

                    Permohonan::where("id", $request->id_permohonan)->update([
                        'id_status' => $id_status,
                    ]);
                    if (isset($request->file_surat) and $request->file('file_surat') != null) {
                        $doc = $request->file('file_surat');
                        $path_doc = $doc->store('file_surat');
                        $permohonanBap = new PermohonanSurat();
                        $permohonanBap->id_permohonan = $request->id_permohonan;
                        $permohonanBap->file = $path_doc;
                        $permohonanBap->save();
                    }
                } else {
                    $id_status = $request->action;
                    $status = StatusDokumen::findOrFail(99);
                    $history = new PermohonanHistory();
                    $history->id_permohonan = $request->id_permohonan;
                    $history->id_status = $status->id_status;
                    $history->nm_status = $status->nama_status;
                    $history->id_verifikator = Auth::user()->id_user;
                    $history->nama_verifikator = Auth::user()->nm_user;
                    $history->tgl_status = $request->tgl;
                    $history->keterangan = $request->keterangan;
                    $history->save();

                    Permohonan::where("id", $request->id_permohonan)->update([
                        'id_status' => $id_status,
                    ]);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Memverifikasi Surat ' . $e->getMessage());
        }
        return redirect()->back()->with('success', 'Berhasil Memverifikasi Data dan Melanjutkan ke Proses Selanjutnya');
    }

    /**
     * Verifikasi Surat the specified resource.
     * @param int $id
     * @return Response
     */
    public function verifikasi_kaban(Request $request)
    {
        try {
            $permohonan = Permohonan::findOrFail($request->id_permohonan);
            $id_status = $permohonan->id_status;
            DB::beginTransaction();
            if ($request->action == 'verifikasi') {
                $id_status = $id_status + 1;
                $status = StatusDokumen::findOrFail($id_status);
                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $id_status;
                $history->nm_status = $status->nama_status;
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl;
                $history->keterangan = $request->keterangan;
                $history->save();

                $noSurat = PermohonanSurat::getAutoNumber();
                if (isset($noSurat)) {
                    PermohonanSurat::where("id", $permohonan->id_surat)->update([
                        'nomer_surat' => $noSurat,
                        'tgl_surat' => date('Y-m-d'),
                    ]);

                    Permohonan::where("id", $request->id_permohonan)->update([
                        'id_status' => $id_status,
                    ]);

                    $id_status = $id_status + 1;
                    $status = StatusDokumen::findOrFail($id_status);
                    $history = new PermohonanHistory();
                    $history->id_permohonan = $request->id_permohonan;
                    $history->id_status = $id_status;
                    $history->nm_status = $status->nama_status;
                    $history->id_verifikator = 'SYSTEM';
                    $history->nama_verifikator = 'SYSTEM';
                    $history->tgl_status = $request->tgl;
                    $history->keterangan = 'Berkas Sudah Bisa diambil';
                    $history->save();
                }
            } else {
                $id_status = $id_status - 1;
                $status = StatusDokumen::findOrFail(99);
                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $status->id_status;
                $history->nm_status = $status->nama_status;
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl;
                $history->keterangan = $request->keterangan;
                $history->save();

                Permohonan::where("id", $request->id_permohonan)->update([
                    'id_status' => $id_status,
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Memverifikasi Permohonan ' . $e->getMessage());
        }
        return redirect()->back()->with('success', 'Berhasil Memverifikasi Permohonan');
    }

    /**
     * Selesaikan Permohonan the specified resource.
     * @param int $id
     * @return Response
     */
    public function selesaikan_proses(Request $request)
    {
        try {
            $permohonan = Permohonan::findOrFail($request->id_permohonan);
            $id_status = $permohonan->id_status;
            DB::beginTransaction();
            if ($request->action == 'verifikasi') {
                $id_status = 11;
                $status = StatusDokumen::findOrFail($id_status);
                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $id_status;
                $history->nm_status = $status->nama_status;
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl ?? date('Y-m-d');
                $history->keterangan = $request->keterangan;
                $history->save();

                Permohonan::where("id", $request->id_permohonan)->update([
                    'id_status' => $id_status,
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Menyelesaikan Surat ' . $e->getMessage());
        }
        return redirect()->back()->with('success', 'Berhasil Menyelesaikan Surat');
    }

    /**
     * Cetak BAP the specified resource.
     * @param int $id
     * @return Response
     */
    public function cetak_bap($id)
    {
        $data["data"] = Permohonan::with('layanan')->findOrFail($id);
        $data["bap"] = PermohonanBap::where('id_permohonan', $id)->orderBy('id', 'DESC')->first();
        $data["history"] = PermohonanHistory::where('id_permohonan', $id)->where('id_status', 3)->first();
        // dd($data);
        $pdf = \PDF::loadview("permohonan::cetak-bap", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    /**
     * Show BAP the specified resource.
     * @param int $id
     * @return Response
     */
    public function show_bap($id)
    {
        $data["data"] = Permohonan::with('layanan')->findOrFail($id);
        $data["bap"] = PermohonanBap::where('id_permohonan', $id)->orderBy('id', 'DESC')->first();
        $path = $data["bap"]->file;
        if (Storage::exists($path)) {
            return Storage::response($path);
        }
    }

    /**
     * Show Keterangan Arsip the specified resource.
     * @param int $id
     * @return Response
     */
    public function show_keterangan_arsip($id)
    {
        $data["data"] = PermohonanArsip::findOrFail($id);
        $pdf = \PDF::loadview("permohonan::cetak-keterangan-arsip", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    /**
     * Show BAP the specified resource.
     * @param int $id
     * @return Response
     */
    public function permohonan_filter(Request $request)
    {
        $statusId = 1;
        $role = 1;
        $url = $request->segment(1);
        switch ($url) {
            case 'permohonan-submit':
                $role = 8;
                break;
            case 'permohonan-bap':
                $role = 9;
                break;
            case 'permohonan-konsep-surat':
                $role = 10;
                break;
            case 'permohonan-verifikasi-ketua':
                $role = 11;
                break;
            case 'permohonan-verifikasi-kabid':
                $role = 5;
                break;
            case 'permohonan-verifikasi-sekretaris':
                $role = 6;
                break;
            case 'permohonan-verifikasi-kaban':
                $role = 7;
                break;
            case 'permohonan-proses-selesai':
                $role = 2;
                break;
            case 'permohonan-selesai':
                $statusId = 11;
                break;
            case 'penyelia-surat':
                $role = 13;
            default:
                $statusId = null;
                break;
        }
        $permohonan = Permohonan::select([
            't_permohonan.id',
            't_permohonan.no_permohonan',
            't_permohonan.tanggal_pengajuan',
            't_permohonan.nama_pemohon',
            't_permohonan.alamat_persil',
            't_permohonan.id_layanan',
            'm_layanan.nm_layanan',
            'posisi_berkas.posisi',
            'role.nm_role',
            'm_status.*'
        ])
            ->distinct()
            ->leftJoin('m_layanan', 't_permohonan.id_layanan', '=', 'm_layanan.id_layanan')
            ->leftJoin('posisi_berkas', function ($join) {
                $join->on('posisi_berkas.id_status', '=', 't_permohonan.id_status')
                    ->on('posisi_berkas.id_layanan', '=', 't_permohonan.id_layanan');
            })
            ->leftJoin('m_status', 't_permohonan.id_status', '=', 'm_status.id_status')
            ->leftJoin('role', 'posisi_berkas.posisi', '=', 'role.id_role')
            ->leftJoin('bidang_layanan', 't_permohonan.id_layanan', '=', 'bidang_layanan.id_layanan')
            ->where('bidang_layanan.bidang', Auth::user()->bidang);

        $currentRole = Session('role')['id_role'];
        $idPetugasSurvey = Auth::user()->id_user;
        if (Session('role')['id_role'] == 1) {
            $permohonan = $permohonan->where('role.id_role', $role);
        } else {
            $permohonan = $permohonan->where('role.id_role', $currentRole)
                ->where(function ($query) use ($idPetugasSurvey) {
                    $query->where('t_permohonan.id_layanan', '!=', 7)
                        ->orWhere(function ($query) use ($idPetugasSurvey) {
                            $query->where('t_permohonan.id_layanan', 7)
                                ->where(function ($query) use ($idPetugasSurvey) {
                                    $query->where('t_permohonan.id_status', '!=', 5)
                                        ->orWhere('t_permohonan.id_petugas_survey', $idPetugasSurvey);
                                });
                        });
                });
        }


        if ($request->id_layanan) {
            $permohonan = $permohonan->where('t_permohonan.id_layanan', $request->id_layanan);
        }

        if ($request->id_permohonan) {
            $permohonan = $permohonan->where('t_permohonan.id', $request->id_permohonan);
        }

        if (isset($request->nm_pemohon)) {
            $permohonan = $permohonan->where('t_permohonan.nama_pemohon', 'LIKE', "%$request->nm_pemohon%");
        }

        if (isset($request->alamat_persil)) {
            $permohonan = $permohonan->where('t_permohonan.alamat_persil', 'LIKE', "%$request->alamat_persil%");
        }

        // $p2bmd = [32, 35, 41, 42];
        // if (in_array(Auth::user()->id_user, $p2bmd)) {
        //     $permohonan = $permohonan->whereIn('id_layanan', [6, 7])->get();
        // }

        $data["data"] = $permohonan->paginate(10);
        $data["layanan"] = Layanan::all();
        $statusDokumen = StatusDokumen::all();
        foreach ($statusDokumen as $key => $value) {
            $data['status'][$value->id_status] = $value;
        }
        $data["filter"] = [
            'nm_filter' => 'filter-permohonan-submit',
            'id_status' => $statusId ?? '',
            'id_layanan' => $request->id_layanan ?? '',
            'permohonan' => isset($request->id_permohonan) ? Permohonan::findOrFail($request->id_permohonan) : '',
            'nm_pemohon' => $request->nm_pemohon,
            'alamat_persil' => $request->alamat_persil,
        ];
        return view('permohonan::index-filter', $data);
    }

    public function monitoring_berkas(Request $request)
    {
        $statusDokumen = StatusDokumen::all();
        foreach ($statusDokumen as $key => $value) {
            $data['status'][$value->id_status] = $value;
        }
        $data["layanan"] = Layanan::all();
        $data["filter"] = [
            'nm_filter' => 'filter-monitoring',
            'id_status' => $statusId ?? '',
            'id_layanan' => $request->id_layanan ?? '',
            'permohonan' => isset($request->id_permohonan) ? Permohonan::findOrFail($request->id_permohonan) : '',
            'nm_pemohon' => $request->nm_pemohon,
            'alamat_persil' => $request->alamat_persil,
        ];

        if (isset($request->id_permohonan)) {
            $data['history'] = PermohonanHistory::getRoleUser($request->id_permohonan, NULL);
            $data["permohonan"] = Permohonan::with('layanan')->leftJoin('posisi_berkas', function ($join) {
                $join->on('posisi_berkas.id_status', '=', 't_permohonan.id_status')
                    ->on('posisi_berkas.id_layanan', '=', 't_permohonan.id_layanan');
            })
                ->leftJoin('role', 'posisi_berkas.posisi', '=', 'role.id_role')
                ->select('t_permohonan.*', 'posisi_berkas.posisi as posisi_berkas', 'role.nm_role as nm_role')
                ->findOrFail($request->id_permohonan);
        }
        return view('permohonan::monitoring-berkas', $data);
    }

    public function monitoring_berkas_show($id)
    {
        $data['data'] = Permohonan::select([
            't_permohonan.id',
            't_permohonan.no_permohonan',
            't_permohonan.tanggal_pengajuan',
            't_permohonan.nama_pemohon',
            't_permohonan.alamat_persil',
            't_permohonan.id_layanan',
            'm_layanan.nm_layanan',
            'posisi_berkas.posisi',
            'role.nm_role',
            'm_status.nama_status',
            't_permohonan.id_status'
        ])
            ->distinct()
            ->leftJoin('m_layanan', 't_permohonan.id_layanan', '=', 'm_layanan.id_layanan')
            ->leftJoin('posisi_berkas', function ($join) {
                $join->on('posisi_berkas.id_status', '=', 't_permohonan.id_status')
                    ->on('posisi_berkas.id_layanan', '=', 't_permohonan.id_layanan');
            })
            ->leftJoin('m_status', 't_permohonan.id_status', '=', 'm_status.id_status')
            ->leftJoin('role', 'posisi_berkas.posisi', '=', 'role.id_role')
            ->where('t_permohonan.id', $id)->first();
        $data['history'] = PermohonanHistory::getRoleUser($id, NULL);
        return view('permohonan::monitoring-berkas-show', $data);
    }

    public function monitoring_berkas_get_data(Request $request)
    {
        $statusId = 11;
        $url = $request->segment(1);
        $idPetugasSurvey = Auth::user()->id_user;
        $permohonan = Permohonan::select([
            't_permohonan.id',
            't_permohonan.no_permohonan',
            't_permohonan.tanggal_pengajuan',
            't_permohonan.nama_pemohon',
            't_permohonan.alamat_persil',
            't_permohonan.id_layanan',
            't_permohonan.id_status',
            'm_layanan.nm_layanan',
            'posisi_berkas.posisi',
            'role.nm_role',
            'm_status.nama_status',
            'users.nm_user',
            DB::raw('(SELECT id_status FROM t_permohonan_history WHERE t_permohonan.id = t_permohonan_history.id_permohonan ORDER BY t_permohonan_history.id DESC LIMIT 1) AS last_status'),
            DB::raw('(SELECT keterangan FROM t_permohonan_history WHERE t_permohonan.id = t_permohonan_history.id_permohonan ORDER BY t_permohonan_history.id DESC LIMIT 1) AS last_keterangan')
        ])
            ->distinct()
            ->leftJoin('m_layanan', 't_permohonan.id_layanan', '=', 'm_layanan.id_layanan')
            ->leftJoin('posisi_berkas', function ($join) {
                $join->on('posisi_berkas.id_status', '=', 't_permohonan.id_status')
                    ->on('posisi_berkas.id_layanan', '=', 't_permohonan.id_layanan');
            })
            ->leftJoin('m_status', 't_permohonan.id_status', '=', 'm_status.id_status')
            ->leftJoin('role', 'posisi_berkas.posisi', '=', 'role.id_role')
            ->leftJoin('bidang_layanan', 't_permohonan.id_layanan', '=', 'bidang_layanan.id_layanan')
            ->leftJoin('users', 't_permohonan.id_petugas_survey', '=', 'users.id_user')
            ->where('bidang_layanan.bidang', Auth::user()->bidang);

        if ($request->url == 'dashboard') {
            $permohonan = $permohonan->where(function ($query) use ($idPetugasSurvey) {
                $query->where('t_permohonan.id_layanan', '!=', 7)
                    ->orWhere(function ($query) use ($idPetugasSurvey) {
                        $query->where('t_permohonan.id_layanan', 7)
                            ->where(function ($query) use ($idPetugasSurvey) {
                                $query->where('t_permohonan.id_status', '!=', 5)
                                    ->orWhere('t_permohonan.id_petugas_survey', $idPetugasSurvey);
                            });
                    });
            });
        }

        if ($request->url == 'permohonan-selesai') {
            $permohonan = $permohonan->where('t_permohonan.id_status', 11);
        } elseif ($request->url == 'permohonan-ditolak') {
            $permohonan = $permohonan->where('t_permohonan.id_status', 99);
        } else {
            $permohonan = $permohonan->where('t_permohonan.id_status', '<=', 11);
        }

        if ($request->url == 'dashboard') {
            $permohonan = $permohonan->where('role.id_role', Session('role')['id_role']);
        }

        // dd($request->all());
        if ($request->id_permohonan) {
            $permohonan = $permohonan->where('t_permohonan.id', $request->id_permohonan);
        }

        if ($request->id_layanan) {
            $permohonan = $permohonan->where('t_permohonan.id_layanan', $request->id_layanan);
        }

        $data = $permohonan->orderBy('t_permohonan.tanggal_pengajuan', 'ASC')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('no_permohonan', function ($row) use ($request) {
                // Jika URL adalah dashboard, tampilkan sebagai link
                if ($request->url == 'dashboard') {
                    $html = '<a href="' . url('permohonan') . '/' . $row->id . '/verifikasi">'
                        . dateindo($row->tanggal_pengajuan) . '<br>'
                        . $row->no_permohonan
                        . '</a>';

                    if ($row->last_status == 99) {
                        $html .= ' <span class="badge bg-danger">Reject</span>'
                            . '<br><span class="text-danger">Keterangan : ' . $row->last_keterangan . '</span>';
                    }

                    return $html;
                }

                // Default tampilkan tanpa link
                $html = dateindo($row->tanggal_pengajuan)
                    . '<br>' . $row->no_permohonan;

                if ($row->last_status == 99) {
                    $html .= ' <span class="badge bg-danger">Reject</span>'
                        . '<br><span class="text-danger">Keterangan : ' . $row->last_keterangan . '</span>';
                }
                return $html;
            })
            ->addColumn('nama_pemohon', function ($row) {
                return $row->nama_pemohon . '<br>' . ($row->alamat_persil);
            })
            ->addColumn('posisi', function ($row) {
                return '<span class="text-muted"> Status : ' . ucwords(strtolower($row->nama_status)) . '</span><br>' . '<b>(' . $row->nm_role . ')</b><br><span class="text-success">' . $row->nm_user . '</span>';
            })
            ->addColumn('status_berkas', function ($row) {
                return ($row->id_status < 11) ? 'Berkas Proses' : 'Berkas Selesai';
            })
            ->addColumn('action', function ($row) {
                $html = '';
                if ($row->id_status != 99) {
                    $html .= '<a href="' . url('permohonan-proses') . '/' . $row->id . '/show" class="btn btn-primary btn-sm me-2">
                        <i class="fa-solid fa-eye"></i>
                    </a>';
                } else {
                    $html .= '<span class="badge bg-danger">Dikembalikan</span>';
                }
                return $html;
            })
            ->rawColumns(['action', 'no_permohonan', 'nama_pemohon', 'posisi'])
            ->make(true);
    }

    public function monitoring_berkas_get_data_cetak(Request $request)
    {
        $statusId = 11;
        $url = $request->segment(1);
        $idPetugasSurvey = Auth::user()->id_user;
        $permohonan = Permohonan::select([
            't_permohonan.id',
            't_permohonan.no_permohonan',
            't_permohonan.tanggal_pengajuan',
            't_permohonan.nama_pemohon',
            't_permohonan.alamat_persil',
            't_permohonan.id_layanan',
            't_permohonan.id_status',
            'm_layanan.nm_layanan',
            'posisi_berkas.posisi',
            'role.nm_role',
            'm_status.nama_status',
            'users.nm_user'
        ])
            ->distinct()
            ->leftJoin('m_layanan', 't_permohonan.id_layanan', '=', 'm_layanan.id_layanan')
            ->leftJoin('posisi_berkas', function ($join) {
                $join->on('posisi_berkas.id_status', '=', 't_permohonan.id_status')
                    ->on('posisi_berkas.id_layanan', '=', 't_permohonan.id_layanan');
            })
            ->leftJoin('m_status', 't_permohonan.id_status', '=', 'm_status.id_status')
            ->leftJoin('role', 'posisi_berkas.posisi', '=', 'role.id_role')
            ->leftJoin('bidang_layanan', 't_permohonan.id_layanan', '=', 'bidang_layanan.id_layanan')
            ->leftJoin('users', 't_permohonan.id_petugas_survey', '=', 'users.id_user')
            ->where('bidang_layanan.bidang', Auth::user()->bidang);

        $permohonan = $permohonan->where(function ($query) use ($idPetugasSurvey) {
            $query->where('t_permohonan.id_layanan', '!=', 7)
                ->orWhere(function ($query) use ($idPetugasSurvey) {
                    $query->where('t_permohonan.id_layanan', 7)
                        ->where(function ($query) use ($idPetugasSurvey) {
                            $query->where('t_permohonan.id_status', '!=', 5)
                                ->orWhere('t_permohonan.id_petugas_survey', $idPetugasSurvey);
                        });
                });
        });

        $permohonan = $permohonan->where('role.id_role', Session('role')['id_role']);

        // dd($request->all());
        if ($request->id_permohonan) {
            $permohonan = $permohonan->where('t_permohonan.id', $request->id_permohonan);
        }

        if ($request->id_layanan) {
            $permohonan = $permohonan->where('t_permohonan.id_layanan', $request->id_layanan);
        }

        $data['data'] = $permohonan->orderBy('t_permohonan.tanggal_pengajuan', 'ASC')->get();
        $data['statusBerkas'] = 'LIST PERMOHONAN YANG HARUS DIPROSES';
        // dd($data);
        $pdf = \PDF::loadview("permohonan::dashboard.cetak", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function cetak_monitoring_berkas(Request $request)
    {
        $statusId = 11;
        $url = $request->segment(1);
        $idPetugasSurvey = Auth::user()->id_user;
        $statusBerkas = 'LIST PERMOHONAN YANG HARUS DIPROSES';
        $permohonan = Permohonan::select([
            't_permohonan.id',
            't_permohonan.no_permohonan',
            't_permohonan.tanggal_pengajuan',
            't_permohonan.nama_pemohon',
            't_permohonan.alamat_persil',
            't_permohonan.id_layanan',
            't_permohonan.id_status',
            'm_layanan.nm_layanan',
            'posisi_berkas.posisi',
            'role.nm_role',
            'm_status.nama_status',
            'users.nm_user'
        ])
            ->distinct()
            ->leftJoin('m_layanan', 't_permohonan.id_layanan', '=', 'm_layanan.id_layanan')
            ->leftJoin('posisi_berkas', function ($join) {
                $join->on('posisi_berkas.id_status', '=', 't_permohonan.id_status')
                    ->on('posisi_berkas.id_layanan', '=', 't_permohonan.id_layanan');
            })
            ->leftJoin('m_status', 't_permohonan.id_status', '=', 'm_status.id_status')
            ->leftJoin('role', 'posisi_berkas.posisi', '=', 'role.id_role')
            ->leftJoin('bidang_layanan', 't_permohonan.id_layanan', '=', 'bidang_layanan.id_layanan')
            ->leftJoin('users', 't_permohonan.id_petugas_survey', '=', 'users.id_user')
            ->where('bidang_layanan.bidang', Auth::user()->bidang);

        if ($request->url == 'dashboard') {
            $permohonan = $permohonan->where(function ($query) use ($idPetugasSurvey) {
                $query->where('t_permohonan.id_layanan', '!=', 7)
                    ->orWhere(function ($query) use ($idPetugasSurvey) {
                        $query->where('t_permohonan.id_layanan', 7)
                            ->where(function ($query) use ($idPetugasSurvey) {
                                $query->where('t_permohonan.id_status', '!=', 5)
                                    ->orWhere('t_permohonan.id_petugas_survey', $idPetugasSurvey);
                            });
                    });
            });
        }

        if ($request->url == 'permohonan-selesai') {
            $statusBerkas = 'LIST PERMOHONAN YANG SELESAI DIPROSES';
            $permohonan = $permohonan->where('t_permohonan.id_status', 11);
        } else {
            $permohonan = $permohonan->where('t_permohonan.id_status', '<', 11);
        }

        if ($request->url == 'cetak-data-dashboard') {
            $permohonan = $permohonan->where('role.id_role', Session('role')['id_role']);
        }

        // dd($request->all());
        if ($request->id_permohonan) {
            $permohonan = $permohonan->where('t_permohonan.id', $request->id_permohonan);
        }

        if ($request->id_layanan) {
            $permohonan = $permohonan->where('t_permohonan.id_layanan', $request->id_layanan);
        }

        $data['data'] = $permohonan->orderBy('t_permohonan.tanggal_pengajuan', 'ASC')->get();
        $data['statusBerkas'] = $statusBerkas;

        // dd($data);
        $pdf = \PDF::loadview("permohonan::dashboard.cetak", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}
