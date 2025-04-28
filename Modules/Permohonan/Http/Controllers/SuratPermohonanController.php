<?php

namespace Modules\Permohonan\Http\Controllers;

use Auth;
use DB;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Permohonan\Entities\Layanan;
use Modules\Permohonan\Entities\LayananForm;
use Modules\Permohonan\Entities\Permohonan;
use Modules\Permohonan\Entities\PermohonanBap;
use Modules\Permohonan\Entities\PermohonanHistory;
use Modules\Permohonan\Entities\PermohonanSurat;
use Modules\Permohonan\Entities\PermohonanArsip;
use Modules\Permohonan\Entities\ListKolektif;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class SuratPermohonanController extends Controller
{
    protected $minListKolektif = 7;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data["layanan"] = Layanan::all();
        $data['filter'] = [
            'id_surat' => isset($request->id_surat) ? PermohonanSurat::findOrFail($request->id_surat) : NULL,
            'id_permohonan' => isset($request->id_surat) ? Permohonan::findOrFail($request->id_permohonan) : NULL,
            'nm_pemohon' => $request->nm_pemohon,
            'alamat_persil' => $request->alamat_persil,
            'id_layanan' => $request->id_layanan,
        ];
        return view('permohonan::surat.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id)
    {
        $data["data"] = Permohonan::with('layanan')->findOrFail($id);
        $data["bap"] = PermohonanBap::where('id_permohonan', $id)->orderBy('id', 'DESC')->first();
        $data["arsip"] = PermohonanArsip::where('id_permohonan', $id)->orderBy('id', 'DESC')->first();
        $data["surat"] = PermohonanSurat::where('id_permohonan', $id)->first();
        $data["form"] = LayananForm::where('id_layanan', $data["data"]->id_layanan)->get();
        // dd($data);
        $data['page_plugin_js'] = [
            'cuba/js/editor/ckeditor/ckeditor.js',
            'cuba/js/editor/ckeditor/adapters/jquery.js',
            'cuba/js/editor/ckeditor/styles.js',
            'cuba/js/editor/ckeditor/ckeditor.custom.js',
        ];
        switch ($data["data"]->id_layanan) {
            case 1:
                return view('permohonan::surat.create-fotocopy-ipt', $data);
                break;
            case 2:
                return view('permohonan::surat.create-rekomendasi-iklan-2', $data);
                break;
            case 3:
                return view('permohonan::surat.create-baliknama-ipt', $data);
                break;
            case 4:
            case 5:
                return view('permohonan::surat.create', $data);
                break;
            case 7:
                return view('permohonan::surat.create-permohonan-status', $data);
                break;
            default:
                return view('permohonan::surat.create-except', $data);
                break;
        }
    }

    /**
     * Create Kolektif the form for creating a new resource.
     * @return Renderable
     */
    public function create_kolektif()
    {
        // $data["data"] = Permohonan::with('layanan')->whereIn('id_layanan', [4, 5])
        //     ->where('id_surat', null)->get();
        $data["data"] = ListKolektif::with('permohonan')->where('is_surat', NULL)->orWhere('is_surat', FALSE)->get();
        $data['page_plugin_js'] = [
            'cuba/js/editor/ckeditor/ckeditor.js',
            'cuba/js/editor/ckeditor/adapters/jquery.js',
            'cuba/js/editor/ckeditor/styles.js',
            'cuba/js/editor/ckeditor/ckeditor.custom.js',
        ];

        return view('permohonan::surat.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        try {
            DB::beginTransaction();
            $surat = new PermohonanSurat();
            $surat->isi = $request->isi;
            $surat->no_persil = $request->no_persil;
            $surat->tgl_ipt = $request->tgl_ipt;
            $surat->nama_pemegang_ipt = $request->nama_pemegang_ipt;
            $surat->alamat_persil = $request->alamat_persil;
            $surat->list_nama = $request->nama;
            $surat->created_by = Auth::user()->id_user;

            if (isset($request->file_surat) and $request->file('file_surat') != null) {
                $doc = $request->file('file_surat');
                $path_doc = $doc->store('file_surat');
                $surat->file = $path_doc;
            }
            $surat->save();

            $permohonan = Permohonan::findOrFail($request->id_permohonan);
            if ($permohonan->id_layanan != 7) {
                Permohonan::where("id", $request->id_permohonan)->update([
                    'id_status' => 4,
                    'id_surat' => $surat->id,
                ]);

                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = 4;
                $history->nm_status = 'PEMBUATAN KONSEP SURAT';
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = date('Y-m-d');
                $history->keterangan = 'Pembuatan Konsep Surat';
                $history->save();
            } else {
                Permohonan::where("id", $request->id_permohonan)->update([
                    'id_status' => 10,
                    'id_surat' => $surat->id,
                ]);

                $history = new PermohonanHistory();
                $history->id_permohonan = $request->id_permohonan;
                $history->id_status = 9;
                $history->nm_status = 'PEMBUATAN KONSEP SURAT';
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = date('Y-m-d');
                $history->keterangan = 'Surat Sudah dibuat';
                $history->save();
            }

            // dd($surat);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Surat Gagal Dibuat ' . $e->getMessage());
        }
        return redirect('permohonan/' . $request->id_permohonan . '/verifikasi')->with('success', 'Konsep Surat Berhasil Dibuat');
    }

    /**
     * Save Kolektif the form for creating a new resource.
     * @return Renderable
     */
    public function save_kolektif(Request $request)
    {
        try {
            DB::beginTransaction();
            $surat = new PermohonanSurat();
            $surat->isi = $request->isi;
            $surat->no_persil = $request->no_persil;
            $surat->tgl_ipt = $request->tgl_ipt;
            $surat->nama_pemegang_ipt = $request->nama_pemegang_ipt;
            $surat->alamat_persil = $request->alamat_persil;
            $surat->list_nama = $request->nama;
            $surat->created_by = Auth::user()->id_user;
            $surat->save();

            foreach ($request->id_permohonan as $key => $value) {
                Permohonan::where("id", $value)->update([
                    'id_status' => 4,
                    'id_surat' => $surat->id,
                ]);

                ListKolektif::where("id_permohonan", $value)->update([
                    'is_surat' => TRUE,
                ]);

                $history = new PermohonanHistory();
                $history->id_permohonan = $value;
                $history->id_status = 4;
                $history->nm_status = 'PEMBUATAN KONSEP SURAT';
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = date('Y-m-d');
                $history->keterangan = 'Pembuatan Konsep Surat';
                $history->save();
            }

            // dd($surat);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Surat Gagal Dibuat ' . $e->getMessage());
        }
        return redirect('permohonan')->with('success', 'Konsep Surat Berhasil Dibuat');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('permohonan::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data["surat"] = PermohonanSurat::findOrFail($id);
        $data['data'] = Permohonan::where('id_surat', $id)->first();
        $layanan = $data['surat']->permohonan->id_layanan;
        // dd($data);
        $data['page_plugin_js'] = [
            'cuba/js/editor/ckeditor/ckeditor.js',
            'cuba/js/editor/ckeditor/adapters/jquery.js',
            'cuba/js/editor/ckeditor/styles.js',
            'cuba/js/editor/ckeditor/ckeditor.custom.js',
        ];
        switch ($layanan) {
            case 1:
                return view('permohonan::surat.create-fotocopy-ipt', $data);
                break;
            case 2:
                return view('permohonan::surat.create-rekomendasi-iklan', $data);
                break;
            case 3:
                return view('permohonan::surat.create-baliknama-ipt', $data);
                break;
            default:
                return view('permohonan::surat.create', $data);
                break;
        }
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
            $surat = PermohonanSurat::findOrFail($id);
            $surat->isi = $request->isi;
            $surat->no_persil = $request->no_persil;
            $surat->tgl_ipt = $request->tgl_ipt;
            $surat->nama_pemegang_ipt = $request->nama_pemegang_ipt;
            $surat->alamat_persil = $request->alamat_persil;
            $surat->list_nama = $request->nama;
            $surat->updated_by = Auth::user()->id_user;
            if (isset($request->file_surat) and $request->file('file_surat') != null) {
                $doc = $request->file('file_surat');
                $path_doc = $doc->store('file_surat');
                $surat->file = $path_doc;
            }
            $surat->save();

            Permohonan::where("id", $surat->permohonan->id)->update([
                'id_status' => 4,
            ]);

            $history = new PermohonanHistory();
            $history->id_permohonan = $surat->permohonan->id;
            $history->id_status = 4;
            $history->nm_status = 'PEMBUATAN KONSEP SURAT';
            $history->id_verifikator = Auth::user()->id_user;
            $history->nama_verifikator = Auth::user()->nm_user;
            $history->tgl_status = date('Y-m-d');
            $history->keterangan = 'Pembuatan Konsep Surat';
            $history->save();

            // dd($surat);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Surat Gagal Dibuat ' . $e->getMessage());
        }
        return redirect('permohonan/' . $surat->permohonan->id . '/verifikasi')->with('success', 'Konsep Surat Berhasil Di Revisi');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update_kolektif(Request $request, $id)
    {

        try {
            DB::beginTransaction();
            $surat = PermohonanSurat::findOrFail($id);
            $surat->isi = $request->isi;
            $surat->no_persil = $request->no_persil;
            $surat->tgl_ipt = $request->tgl_ipt;
            $surat->nama_pemegang_ipt = $request->nama_pemegang_ipt;
            $surat->alamat_persil = $request->alamat_persil;
            $surat->list_nama = $request->nama;
            $surat->updated_by = Auth::user()->id_user;
            $surat->save();

            Permohonan::where("id_surat", $id)->update([
                'id_status' => 4,
            ]);

            $history = new PermohonanHistory();
            $history->id_permohonan = $surat->permohonan->id;
            $history->id_status = 4;
            $history->nm_status = 'REVISI KONSEP SURAT';
            $history->id_verifikator = Auth::user()->id_user;
            $history->nama_verifikator = Auth::user()->nm_user;
            $history->tgl_status = date('Y-m-d');
            $history->keterangan = 'Revisi Konsep Surat';
            $history->save();

            // dd($surat);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Surat Gagal Dibuat ' . $e->getMessage());
        }
        return redirect('permohonan/' . $surat->permohonan->id . '/verifikasi')->with('success', 'Konsep Surat Berhasil Di Revisi');
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

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function save_list(Request $request)
    {
        if (!isset($request->id_permohonan)) {
            return redirect()->back()->with('error', 'Harap Pilih Permohonan Terlebih Dahulu ');
        }
        $totalPermohonan = count($request->id_permohonan);
        try {
            DB::beginTransaction();
            ListKolektif::where('is_surat', NULL)->orWhere('is_surat', FALSE)->update(['deleted_at' => now()]);
            foreach ($request->id_permohonan as $key => $value) {
                $list = new ListKolektif();
                $list->id_permohonan = $value;
                $list->is_surat = FALSE;
                $list->save();

                $history = new PermohonanHistory();
                $history->id_permohonan = $value;
                $history->id_status = 3;
                $history->nm_status = 'MENUNGGU KUOTA TERPENUHI';
                $history->id_verifikator = Auth::user()->id_user;
                $history->nama_verifikator = Auth::user()->nm_user;
                $history->tgl_status = date('Y-m-d');
                $history->keterangan = 'DATA MASUK ' . $totalPermohonan . ' KURANG ' . $this->minListKolektif - $totalPermohonan;
                $history->save();
            }

            // dd($surat);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Antrian Gagal Dibuat ' . $e->getMessage());
        }
        return redirect()->back()->with('success', 'Berhasil Memasukkan Data Ke Antrian ');
    }

    /**
     * Cetak the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function cetak($id)
    {
        $data["surat"] = PermohonanSurat::findOrFail($id);
        $data['data'] = Permohonan::where('id_surat', $id)->first();
        $layanan = $data['surat']->permohonan->id_layanan;

        switch ($layanan) {
            case 1:
                $pdf = \PDF::loadview("permohonan::cetak-fotocopy-ipt", $data)
                    ->setOptions(['defaultFont' => 'Arial'])->setPaper('A4', 'portrait');
                break;
            case 2:
                $pdf = \PDF::loadview("permohonan::cetak-surat-rekom-iklan-2", $data)
                    ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');
                break;
            case 3:
                $pdf = \PDF::loadview("permohonan::cetak-surat-balik-nama", $data)
                    ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');
                break;
            case 7:
                return redirect()->route('show-document', [
                    'pdf' => $data['surat']->file,
                ]);
            default:
                $pdf = \PDF::loadview("permohonan::cetak-surat", $data)
                    ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');
                break;
        }

        return $pdf->stream();
    }

    public function cetak_dengan_qr($id)
    {
        $data["surat"] = PermohonanSurat::findOrFail($id);
        $data['data'] = Permohonan::where('id_surat', $id)->first();
        return view('permohonan::surat.qrcode', $data);
    }

    public function store_qr(Request $request)
    {
        $data['qrBase64'] = $request->input('qrcode');
        $id = $request->id_surat;
        $data["surat"] = PermohonanSurat::findOrFail($id);
        $data['data'] = Permohonan::where('id_surat', $id)->first();
        $layanan = $data['surat']->permohonan->id_layanan;
        // dd($data);

        switch ($layanan) {
            case 1:
                $pdf = \PDF::loadview("permohonan::cetak-fotocopy-ipt", $data)
                    ->setOptions(['defaultFont' => 'Arial'])->setPaper('A4', 'portrait');
                break;
            case 2:
                $pdf = \PDF::loadview("permohonan::qrcode.cetak-surat-rekom-iklan", $data)
                    ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');
                break;
            case 3:
                $pdf = \PDF::loadview("permohonan::qrcode.cetak-surat-balik-nama", $data)
                    ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');
                break;
            case 7:
                return redirect()->route('show-document', [
                    'pdf' => $data['surat']->file,
                ]);
            default:
                $pdf = \PDF::loadview("permohonan::qrcode.cetak-surat-balik-nama", $data)
                    ->setOptions(['defaultFont' => 'Tahoma'])->setPaper('A4', 'portrait');
                break;
        }

        return $pdf->stream();
    }

    public function get_data_surat(Request $request)
    {
        $surat = PermohonanSurat::leftJoin('t_permohonan', 't_permohonan.id_surat', '=', 't_permohonan_surat.id')
            ->leftJoin('m_layanan', 't_permohonan.id_layanan', '=', 'm_layanan.id_layanan')
            ->select('t_permohonan_surat.*', 't_permohonan.*', 't_permohonan.id as id_permohonan', 't_permohonan.no_permohonan', 'm_layanan.nm_layanan')
            ->orderBy('t_permohonan_surat.id', 'DESC');

        if (isset($request->id_surat)) {
            $surat = $surat->where('t_permohonan_surat.id', $request->id_surat);
        }

        if (isset($request->id_permohonan)) {
            $surat = $surat->where('t_permohonan.id', $request->id_permohonan);
        }

        if (isset($request->nm_pemohon)) {
            $surat = $surat->where('t_permohonan.nama_pemohon', 'LIKE', "%$request->nm_pemohon%");
        }

        if (isset($request->alamat_persil)) {
            $surat = $surat->where('t_permohonan.alamat_persil', 'LIKE', "%$request->alamat_persil%");
        }

        if (isset($request->id_layanan)) {
            $surat = $surat->where('m_layanan.id_layanan', $request->id_layanan);
        }

        $data = $surat->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('no_surat', function ($row) {
                $html = $row->nomer_surat ? '500.16.7.2 /' . $row->nomer_surat . '.SK/436.8.2/' . date('Y', strtotime($row->tgl_surat)) : 'e-surat' ;
                return $html;
            })
            ->addColumn('no_permohonan', function ($row) {
                return $row->no_permohonan . '<br>' . dateindo($row->tanggal_pengajuan);
            })
            ->addColumn('nama_pemohon', function ($row) {
                return $row->nama_pemohon . '<br>' . ($row->alamat_persil);
            })
            ->addColumn('nm_layanan', function ($row) {
                return $row->nm_layanan;
            })
            ->addColumn('action', function ($row) {
                $html = '
                <div class="d-flex">
                    <a href="' . url('surat') . '/' . $row->id_surat . '/cetak-surat" class="btn btn-primary btn-sm me-2">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                    <a href="' . url('permohonan') . '/' . $row->id_permohonan . '/verifikasi#data-syarat" class="btn btn-primary btn-sm me-2">
                        <i class="fa-solid fa-file"></i>
                    </a>
                    
                ';
                if ($row->id_status <= 4) {
                    $html .= '<a class="btn btn-sm btn-danger btn-sm"
                        href="' . url('surat') . '/' . $row->id_surat . '/edit" class="btn btn-primary btn-sm me-2">
                        <i class="fa-solid fa-pencil"></i>
                    </a>';
                }
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['action', 'no_permohonan', 'nama_pemohon', 'nm_layanan', 'no_surat'])
            ->make(true);
    }
}
