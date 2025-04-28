<?php

namespace Modules\Permohonan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Permohonan\Entities\LayananDocument;
use Modules\Permohonan\Entities\LayananForm;
use Modules\Permohonan\Entities\PenguranganIpt;
use Modules\Permohonan\Entities\PenguranganIptStatus;
use Modules\Permohonan\Entities\PenguranganIptBap;
use Modules\Permohonan\Entities\PenguranganIptSurat;
use Modules\Permohonan\Http\Requests\PenguranganIptRequest;
use Auth;
use DB;
use Modules\Permohonan\Entities\PenguranganIptDocument;
use Illuminate\Support\Facades\Validator;
use Modules\Permohonan\Entities\PermohonanHistory;
use Modules\Permohonan\Entities\StatusDokumen;
use stdClass;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PenguranganIptController extends Controller
{
    /**
     * Trait.
     * @return Response
     */

     use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $permohonan = PenguranganIpt::with('status')
            ->select(
                'ipt_pengurangan.*',
                DB::raw('(SELECT id_status FROM t_permohonan_history WHERE ipt_pengurangan.id = t_permohonan_history.id_permohonan AND t_permohonan_history.type = "ipt-pengurangan" ORDER BY t_permohonan_history.id DESC LIMIT 1) AS last_status'),
                DB::raw('(SELECT keterangan FROM t_permohonan_history WHERE ipt_pengurangan.id = t_permohonan_history.id_permohonan AND t_permohonan_history.type = "ipt-pengurangan" ORDER BY t_permohonan_history.id DESC LIMIT 1) AS last_keterangan')
            );

        if (Session('role')['id_role'] == 99) {
            $permohonan = $permohonan->orderBy('id', 'DESC');
        } else {
            $permohonan = $permohonan->where('id_status', '<', 10)->orderBy('id', 'ASC');
        }

        if ($request->id_status) {
            $permohonan = $permohonan->where('id_status', $request->id_status);
        }

        if ($request->id_permohonan) {
            $permohonan = $permohonan->where('ipt_pengurangan.id', $request->id_permohonan);
        }

        if (isset($request->nm_pemohon)) {
            $permohonan = $permohonan->where('ipt_pengurangan.nama_pemohon', 'LIKE', "%$request->nm_pemohon%");
        }

        if (isset($request->alamat_persil)) {
            $permohonan = $permohonan->where('ipt_pengurangan.alamat_persil', 'LIKE', "%$request->alamat_persil%");
        }

        if (Session('role')['id_role'] == 99) {
            $permohonan->where('id_user', Auth::user()->id_user);
        }

        $data["data"] = $permohonan->paginate(10);
        $data["filter"] = [
            'id_status' => $request->id_status ?? '',
            'id_layanan' => $request->id_layanan ?? '',
            'permohonan' => isset($request->id_permohonan) ? Permohonan::findOrFail($request->id_permohonan) : '',
            'nm_pemohon' => $request->nm_pemohon,
            'alamat_persil' => $request->alamat_persil,
        ];
        return view('permohonan::ipt-pengurangan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        if (isset($request->tipe)) {
            $data["document"] = LayananDocument::where('id_layanan', $request->tipe)->get();
            $data["form"] = LayananForm::where('id_layanan', $request->tipe)->get();
            $data["type"] = PenguranganIpt::$types;
        }
        return view('permohonan::ipt-pengurangan.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(PenguranganIptRequest $request)
    {
        try {
            DB::beginTransaction();
            $permohonan = new PenguranganIpt();
            $permohonan->nik = $request->nik ?? '';
            $permohonan->type = $request->type ?? '';
            $permohonan->no_permohonan = "BPKAD/IPT-P/" . date("ymd") . '/' . substr(crc32(uniqid()), -4);
            $permohonan->nama_pemohon = $request->nama_pemohon ?? '';
            $permohonan->telepon_pemohon = $request->telepon_pemohon ?? '';
            $permohonan->no_sk = $request->no_sk ?? '';
            $permohonan->penggunaan = $request->penggunaan ?? '';
            $permohonan->alamat_persil = $request->alamat_persil ?? '';
            $permohonan->tanggal_pengajuan = $request->tanggal_pengajuan ?? date('Y-m-d');
            $permohonan->id_status = 100;
            $permohonan->id_user = Auth::user()->id_user;

            $permohonan->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Pembuatan Permohonan gagal' . $e->getMessage());
        }

        return redirect('ipt-pengurangan/' . $permohonan->id . '/detail')->with('success', 'Pembuatan Permohonan berhasil');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data["data"] = PenguranganIpt::findOrFail($id);
        $this->authorize('view', $data['data']);
        $document = PenguranganIptDocument::where('id_permohonan', $id)->get();
        $arrDoc = [];
        foreach ($document as $key => $value) {
            $filename = explode("/", $value->file);
            $arrDoc[$filename[0]] = $value->file;
        }
        $data["form"] = LayananForm::where('id_layanan', 8)->get();
        $data["document"] = LayananDocument::where('id_layanan', 8)->get();
        $data["dataDocument"] = $arrDoc;
        $data["history"] = PermohonanHistory::where('id_permohonan', $id)->where('type', 'ipt-pengurangan')->get();
        return view('permohonan::ipt-pengurangan.show', $data);
    }

    /**
     * History the specified resource.
     * @param int $id
     * @return Response
     */
    public function history($id)
    {
        $data["data"] = PenguranganIpt::with('layanan')->findOrFail($id);        
        $this->authorize('view', $data['data']);
        $data["history"] = PermohonanHistory::where('id_permohonan', $id)->where('type', 'ipt-pengurangan')->get();

        // dd($data);
        return view('permohonan::history', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data["data"] = PenguranganIpt::findOrFail($id);
        $document = PenguranganIptDocument::where('id_permohonan', $id)->get();
        $arrDoc = [];
        foreach ($document as $key => $value) {
            $filename = explode("/", $value->file);
            $arrDoc[$filename[0]] = $value->file;
        }
        $data["dataDocument"] = $arrDoc;
        $data["document"] = LayananDocument::where('id_layanan', 8)->get();
        $data["form"] = LayananForm::where('id_layanan', 8)->get();
        $data["type"] = PenguranganIpt::$types;
        // dd($data);
        return view('permohonan::ipt-pengurangan.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $permohonan = PenguranganIpt::findOrFail($id);
            $permohonan->nik = $request->nik ?? '';
            $permohonan->type = $request->type ?? '';
            $permohonan->nama_pemohon = $request->nama_pemohon ?? '';
            $permohonan->telepon_pemohon = $request->telepon_pemohon ?? '';
            $permohonan->no_sk = $request->no_sk ?? '';
            $permohonan->penggunaan = $request->penggunaan ?? '';
            $permohonan->alamat_persil = $request->alamat_persil ?? '';
            $permohonan->tanggal_pengajuan = $request->tanggal_pengajuan ?? date('Y-m-d');  
            $permohonan->id_user = Auth::user()->id_user;
            $permohonan->save();


            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Update Permohonan gagal' . $e->getMessage());
        }

        return redirect('ipt-pengurangan/' . $id . '/detail')->with('success', 'Update Permohonan berhasil');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function upload_file(Request $request)
    {
        // dd($request->all());
        try {
            $permohonan = PenguranganIpt::findOrFail($request->id_permohonan);
            $dokumen = LayananDocument::where('id_layanan', 8)->get();
            foreach ($dokumen as $key => $value) {
                $namaDocument = change_form($value->nama_document);
                if (isset($request->$namaDocument) and $request->file($namaDocument) != null) {
                    $validator = Validator::make($request->all(), [
                        $namaDocument => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
                    ]);
                    if ($validator->fails()) {
                        return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
                    }
                    $doc = $request->file($namaDocument);
                    $path_doc = $doc->store($namaDocument);
                    $permohonanDocument = new PenguranganIptDocument();
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

    public function submit_data($id)
    {
        try {
            $status = false;
            $permohonan = PenguranganIpt::findOrFail($id);
            $dokumen = LayananDocument::where('id_layanan', $permohonan->id_layanan)->get();
            $permohonanDokumen = PenguranganIptDocument::where('id_permohonan', operator: $id)->get();
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
                PenguranganIpt::where("id", $permohonan->id)->update([
                    'id_status' => 1,
                ]);

                $history = new PermohonanHistory();
                $history->id_permohonan = $permohonan->id;
                $history->id_status = 1;
                $history->type = 'ipt-pengurangan';
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
     * Cetak Permohnan the specified resource.
     * @param int $id
     * @return Response
     */
    public function cetak($id)
    {
        $data["data"] = PenguranganIpt::findOrFail($id);
        $this->authorize('view', $data['data']);
        $document = PenguranganIptDocument::where('id_permohonan', $id)->get();
        $arrDoc = [];
        foreach ($document as $key => $value) {
            $filename = explode("/", $value->file);
            $arrDoc[$filename[0]] = $value->file;
        }
        $data["form"] = LayananForm::where('id_layanan', 8)->get();
        $data["document"] = LayananDocument::where('id_layanan', 8)->get();
        $data["dataDocument"] = $arrDoc;

        $pdf = \PDF::loadview("permohonan::ipt-pengurangan.cetak-permohonan", $data)
            ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function verifikasi($id)
    {
        $data["data"] = PenguranganIpt::with('layanan')->findOrFail($id);
        $document = PenguranganIptDocument::where('id_permohonan', $id)->get();
        $arrDoc = [];
        foreach ($document as $key => $value) {
            $filename = explode("/", $value->file);
            $arrDoc[$filename[0]] = $value->file;
        }
        $data["form"] = LayananForm::where('id_layanan', 8)->get();
        $data["document"] = LayananDocument::where('id_layanan', 8)->get();
        $data["dataDocument"] = $arrDoc;
        $data["type"] = PenguranganIpt::$types;
        $data["bap"] = PenguranganIptBap::where('id_permohonan', $id)->orderBy('id', 'DESC')->first();
        $skrd = [];

        if (isset($data["bap"])) {
            $url = "https://sigasda.surabaya.go.id/api/DbPenetapanRetribusi";
            $headers = [
                "username: Sekre@admin2025!",
                "password: S3kr3@4dm1n",
                "Content-Type: application/json"
            ];
            $variable = [
                'id_persil' => $data['data']->no_sk,
                'nop' => '0',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($variable));
            $response = curl_exec($ch);
            $response = json_decode($response, true);

            // Cek error
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
                return redirect()->back()->with('error', 'Gagal Mendapatkan data dari sigasda, cek kembali no SK anda atau hubungi Administrator');
            } else {
                if (isset($response['success']) && $response['success']) {
                    $skrd = json_decode(json_encode($response['data'][0]));
                } else {
                    $skrd = [];
                }
            }
            curl_close($ch);
        }

        switch ($data['data']->id_status) {
            case 1:
                $data['status'] = [
                    '99' => 'Kembalikan Data Ke Pemohon (Reject)',
                    '2' => 'Lanjutkan Proses Selanjutnya'
                ];
                break;

            case 2:
                $data['status'] = [
                    '99' => 'Kembalikan Data Ke Pemohon (Reject)',
                    '1' => 'Kembalikan Data Ke Petugas BAP',
                    '3' => 'Lanjutkan Proses Ke Petugas SK'
                ];
                break;

            case 3:
                $data['status'] = [
                    '99' => 'Kembalikan Data Ke Pemohon (Reject)',
                    '1' => 'Kembalikan Data Ke Petugas BAP',
                    '2' => 'Kembalikan Data Ke Petugas SK',
                    '4' => 'Lanjutkan Proses Ke Kabid'
                ];
                break;

            case 4:
                $data['status'] = [
                    '99' => 'Kembalikan Data Ke Pemohon (Reject)',
                    '1' => 'Kembalikan Data Ke Petugas BAP',
                    '2' => 'Kembalikan Data Ke Petugas SK',
                    '3' => 'Kembalikan Data Ke Ketua',
                    '5' => 'Lanjutkan Proses Ke Sekretaris'
                ];
                break;

            case 5:
                $data['status'] = [
                    '4' => 'Kembalikan Data Ke Kabid',
                    '6' => 'Lanjutkan Proses Ke Verifikasi KA BPKAD',
                ];
                break;

            default:
                $data['status'] = [
                    '2' => 'Belum Disetting',
                ];
                break;
        }
        if (isset($data['data']->id_surat)) {
            $data["surat"] = PenguranganIptSurat::findOrFail($data['data']->id_surat);
        } else {
            $data["surat"] = null;
        }
        $data["skrd"] = $skrd;
        // dd($data);
        return view('permohonan::ipt-pengurangan.verifikasi', $data);
    }

    /**
     * Upload BAP the specified resource.
     * @param int $id
     * @return Response
     */
    public function upload_bap(Request $request)
    {
        // dd($request->all());
        try {
            $permohonan = PenguranganIpt::findOrFail($request->id_permohonan);
            $id_status = $permohonan->id_status;
            DB::beginTransaction();
            if ($request->action == '2') {
                $array = [
                    'peruntukan' => 'required',
                    'penggunaan' => 'required',
                    'type' => 'required',
                    'no_ipt' => 'required',
                    'tanggal_ipt' => 'required',
                    'luas' => 'required',
                    'file_bap' => 'required|mimes:pdf,jpg,jpeg|max:2048',
                ];
                $validator = Validator::make($request->all(), $array);

                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }

                $cek = $this->getSigasda($request->no_ipt);
                if (!$cek) {
                    return redirect()->back()->with('error', 'Gagal Upload BAP, Periksa Kembali No SK / ID Persil yang anda isi. Atau Hubungi Administrator');
                }

                $id_status = $id_status + 1;
                $status = PenguranganIptStatus::findOrFail($id_status);
                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $id_status;
                $history->type = 'ipt-pengurangan';
                $history->nm_status = $status->nama_status;
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl;
                $history->keterangan = $request->keterangan;
                $history->save();

                PenguranganIpt::where("id", $request->id_permohonan)->update([
                    'id_status' => $id_status,
                    'type' => $request->type,
                    'no_sk' => $request->no_ipt,
                ]);

                if (isset($request->file_bap) and $request->file('file_bap') != null) {

                    PenguranganIptBap::where('id_permohonan', $request->id_permohonan)->update(['deleted_at' => now()]);

                    $doc = $request->file('file_bap');
                    $path_doc = $doc->store('file_bap');
                    $permohonanBap = new PenguranganIptBap();
                    $permohonanBap->id_permohonan = $request->id_permohonan;
                    $permohonanBap->file = $path_doc;
                    $permohonanBap->peruntukan = $request->peruntukan;
                    $permohonanBap->penggunaan = $request->penggunaan;
                    $permohonanBap->no_ipt = $request->no_ipt;
                    $permohonanBap->tanggal_ipt = $request->tanggal_ipt;
                    $permohonanBap->luas = $request->luas;
                    $permohonanBap->no_skrd = $request->no_ipt . '/ST.TAHUN/BPKAD/' . date('Y');
                    $permohonanBap->save();
                }
            } else {
                $id_status = $request->action;
                $status = PenguranganIptStatus::findOrFail(99);
                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $status->id_status;
                $history->nm_status = $status->nama_status;
                $history->type = 'ipt-pengurangan';
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl;
                $history->keterangan = $request->keterangan;
                $history->save();

                PenguranganIpt::where("id", $request->id_permohonan)->update([
                    'id_status' => $id_status,
                    // 'is_lengkap' => 0,
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Upload BAP ' . $e->getMessage());
        }
        if ($request->action == '2') {
            return redirect()->back()->with('success', 'Berhasil Memverifikasi Data dan melanjutkan ke Proses Selanjutnya');
        } else {
            return redirect()->back()->with('error', 'Berhasil mengembalikan ke proses sebelumnya');
        }
    }

    public function verifikasi_surat(Request $request)
    {
        try {
            $permohonan = PenguranganIpt::findOrFail($request->id_permohonan);
            $permohonanStatus = $permohonan->id_status;
            DB::beginTransaction();
            if ($request->action > $permohonanStatus) {
                $id_status = $request->action;
                $status = PenguranganIptStatus::findOrFail($id_status);
                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $id_status;
                $history->nm_status = $status->nama_status;
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl;
                $history->keterangan = $request->keterangan;
                $history->type = 'ipt-pengurangan';
                $history->save();

                PenguranganIpt::where("id", $request->id_permohonan)->update([
                    'id_status' => $id_status,
                ]);
                if (isset($request->file_surat) and $request->file('file_surat') != null) {
                    $doc = $request->file('file_surat');
                    $path_doc = $doc->store('file_surat');
                    $permohonanBap = new PenguranganIptSurat();
                    $permohonanBap->id_permohonan = $request->id_permohonan;
                    $permohonanBap->file = $path_doc;
                    $permohonanBap->save();
                }
            } else {
                $id_status = $request->action;
                $status = PenguranganIptStatus::findOrFail(99);
                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $status->id_status;
                $history->nm_status = $status->nama_status;
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl;
                $history->keterangan = $request->keterangan;
                $history->type = 'ipt-pengurangan';
                $history->save();

                PenguranganIpt::where("id", $request->id_permohonan)->update([
                    'id_status' => $id_status,
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Memverifikasi Surat ' . $e->getMessage());
        }
        if ($request->action > $permohonanStatus) {
            return redirect()->back()->with('success', 'Berhasil Memverifikasi Data dan Melanjutkan ke Proses Selanjutnya');
        } else {
            return redirect()->back()->with('success', 'Berhasil Mengembalikan ke Proses sebelumnya');
        }
    }

    public function do_verifikasi(Request $request)
    {
        try {
            $permohonan = PenguranganIpt::findOrFail($request->id_permohonan);
            $permohonanStatus = $permohonan->id_status;
            DB::beginTransaction();
            if ($request->action > $permohonanStatus) {
                $id_status = $request->action;
                $status = PenguranganIptStatus::findOrFail($id_status);
                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $id_status;
                $history->nm_status = $status->nama_status;
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl;
                $history->keterangan = $request->keterangan;
                $history->type = 'ipt-pengurangan';
                $history->save();

                PenguranganIpt::where("id", $request->id_permohonan)->update([
                    'id_status' => $id_status,
                ]);
            } else {
                $id_status = $request->action;
                $status = PenguranganIptStatus::findOrFail(99);
                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $status->id_status;
                $history->nm_status = $status->nama_status;
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl;
                $history->keterangan = $request->keterangan;
                $history->type = 'ipt-pengurangan';
                $history->save();

                PenguranganIpt::where("id", $request->id_permohonan)->update([
                    'id_status' => $id_status,
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal memverifikasi ' . $e->getMessage());
        }
        if ($request->action > $permohonanStatus) {
            return redirect()->back()->with('success', 'Berhasil Memverifikasi Data dan Melanjutkan ke Proses Selanjutnya');
        } else {
            return redirect()->back()->with('success', 'Berhasil Mengembalikan ke Proses sebelumnya');
        }
    }
    public function verifikasi_kaban(Request $request)
    {
        try {
            $permohonan = PenguranganIpt::findOrFail($request->id_permohonan);
            $id_status = $permohonan->id_status;
            DB::beginTransaction();
            if ($request->action == 'verifikasi') {
                $id_status = $id_status + 1;
                $status = PenguranganIptStatus::findOrFail($id_status);
                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $id_status;
                $history->nm_status = $status->nama_status;
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl;
                $history->keterangan = $request->keterangan;
                $history->type = 'ipt-pengurangan';
                $history->save();

                $noSurat = PenguranganIptSurat::getAutoNumber();
                if (isset($noSurat)) {
                    PenguranganIptSurat::where("id", $permohonan->id_surat)->update([
                        'nomer_surat' => $noSurat,
                        'tgl_surat' => date('Y-m-d'),
                    ]);

                    PenguranganIpt::where("id", $request->id_permohonan)->update([
                        'id_status' => $id_status,
                    ]);

                    $id_status = $id_status + 1;
                    $status = PenguranganIptStatus::findOrFail(8);
                    $history = new PermohonanHistory();
                    $history->id_permohonan = $request->id_permohonan;
                    $history->id_status = $id_status;
                    $history->nm_status = $status->nama_status;
                    $history->id_verifikator = 'SYSTEM';
                    $history->nama_verifikator = 'SYSTEM';
                    $history->tgl_status = $request->tgl;
                    $history->keterangan = 'Berkas Sudah Bisa diambil';
                    $history->type = 'ipt-pengurangan';
                    $history->save();
                }
            } else {
                $id_status = $id_status - 1;
                $status = PenguranganIptStatus::findOrFail(99);
                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $status->id_status;
                $history->nm_status = $status->nama_status;
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl;
                $history->keterangan = $request->keterangan;
                $history->type = 'ipt-pengurangan';
                $history->save();

                PenguranganIpt::where("id", $request->id_permohonan)->update([
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
            $permohonan = PenguranganIpt::findOrFail($request->id_permohonan);
            $id_status = $permohonan->id_status;
            DB::beginTransaction();
            if ($request->action == 'verifikasi') {
                $id_status = 10;
                $status = PenguranganIptStatus::findOrFail($id_status);
                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = $id_status;
                $history->nm_status = $status->nama_status;
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = $request->tgl ?? date('Y-m-d');
                $history->keterangan = $request->keterangan;
                $history->type = 'ipt-pengurangan';
                $history->save();

                PenguranganIpt::where("id", $request->id_permohonan)->update([
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

    public function getSigasda($sk)
    {
        $url = "https://sigasda.surabaya.go.id/api/DbPenetapanRetribusi";
        $headers = [
            "username: Sekre@admin2025!",
            "password: S3kr3@4dm1n",
            "Content-Type: application/json"
        ];
        $variable = [
            'id_persil' => $sk,
            'nop' => '0',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($variable));
        $response = curl_exec($ch);
        $response = json_decode($response, true);

        // Cek error
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            return redirect()->back()->with('error', 'Gagal Mendapatkan data dari sigasda, cek kembali no SK anda atau hubungi Administrator');
        } else {
            if (isset($response['success']) && $response['success']) {
                $skrd = true;
            } else {
                $skrd = false;
            }
        }
        curl_close($ch);
        return $skrd;
    }
}
