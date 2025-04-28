<?php

namespace Modules\Arsip\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Auth;
use DB;
use Modules\Arsip\Entities\Arsip;
use Modules\Arsip\Entities\ArsipDocument;
use Illuminate\Support\Facades\Validator;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class ArsipController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('arsip::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('arsip::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'nama_pemohon' => 'required',
            'alamat_persil' => 'required',
            'tanggal_pengajuan' => 'required',
            'document_persyaratan' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
            'document_bap' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
            'document_surat' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $arsip = new Arsip();
            $arsip->nama_pemohon = $request->nama_pemohon;
            $arsip->alamat_persil = $request->alamat_persil;
            $arsip->tanggal_pengajuan = $request->tanggal_pengajuan;
            $arsip->user_id = Auth::user()->id_user;
            $arsip->save();

            $arsipDocument = new ArsipDocument();
            $arsipDocument->arsip_id = $arsip->id;
            // Document Persyaratan
            $document_persyaratan = $request->file('document_persyaratan');
            $document_persyaratan_path = $document_persyaratan->store('document_persyaratan');
            $arsipDocument->document_persyaratan = $document_persyaratan_path;
            // Document BAP
            $document_bap = $request->file('document_bap');
            $document_bap_path = $document_bap->store('document_bap');
            $arsipDocument->document_bap = $document_bap_path;
            // Document Surat
            $document_surat = $request->file('document_surat');
            $document_surat_path = $document_surat->store('document_surat');
            $arsipDocument->document_surat = $document_surat_path;
            $arsipDocument->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Menyimpan Data ' . $e->getMessage());
        }
        return redirect('arsip' . '/' . $arsip->id . '/detail')->with('success', 'Berhasil Menyimpan Data');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data["data"] = Arsip::with('arsipDocument')->findOrFail($id);
        return view('arsip::show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data["data"] = Arsip::with('arsipDocument')->findOrFail($id);
        return view('arsip::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'nama_pemohon' => 'required',
            'alamat_persil' => 'required',
            'tanggal_pengajuan' => 'required',
            'document_persyaratan' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
            'document_bap' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
            'document_surat' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $arsip = Arsip::findOrFail($id);
            $arsip->nama_pemohon = $request->nama_pemohon;
            $arsip->alamat_persil = $request->alamat_persil;
            $arsip->tanggal_pengajuan = $request->tanggal_pengajuan;
            $arsip->save();

            $arsipDocument = ArsipDocument::where('arsip_id', $arsip->id)->first();
            // Document Persyaratan
            $document_persyaratan = $request->file('document_persyaratan');
            $document_persyaratan_path = $document_persyaratan->store('document_persyaratan');
            $arsipDocument->document_persyaratan = $document_persyaratan_path;
            // Document BAP
            $document_bap = $request->file('document_bap');
            $document_bap_path = $document_bap->store('document_bap');
            $arsipDocument->document_bap = $document_bap_path;
            // Document Surat
            $document_surat = $request->file('document_surat');
            $document_surat_path = $document_surat->store('document_surat');
            $arsipDocument->document_surat = $document_surat_path;
            $arsipDocument->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Menyimpan Data ' . $e->getMessage());
        }
        return redirect('arsip' . '/' . $arsip->id . '/detail')->with('success', 'Berhasil Menyimpan Data');
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

    public function get_data(Request $request)
    {
        $data = Arsip::where('arsip.id', '>', 0);

        if (isset($request->url)) {
            $url = (string) $request->url;
            $tahun = explode('-', $url);
            if (count($tahun) > 1) {
                $tahunFilter = $tahun[1];
                $data = $data->where(DB::raw('YEAR(tanggal_pengajuan)'), $tahunFilter);
            }
        }

        $data = $data->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('tanggal_pengajuan', function ($row) {
                return dateindo($row->tanggal_pengajuan);
            })
            ->addColumn('action', function ($row) {
                return '
                <div class="d-flex">
                    <a href="' . url('arsip') . '/' . $row->id . '/detail" class="btn btn-primary btn-sm me-2">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                    <a href="' . url('arsip') . '/' . $row->id . '/edit" class="btn btn-warning btn-sm me-2">
                        <i class="fa-solid fa-pencil"></i>
                    </a>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
