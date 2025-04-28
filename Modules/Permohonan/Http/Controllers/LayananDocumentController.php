<?php

namespace Modules\Permohonan\Http\Controllers;

use Auth;
use DB;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Permohonan\Entities\Layanan;
use Modules\Permohonan\Entities\LayananDocument;

class LayananDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data['data'] = Layanan::paginate(10);
        return view('permohonan::layanan-dokumen.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('permohonan::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_layanan' => 'required',
            'nama_document' => 'required',
            'status' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $layanan = new LayananDocument();
            $layanan->id_layanan = $request->id_layanan;
            $layanan->nama_document = $request->nama_document;
            $layanan->status = $request->status;
            $layanan->keterangan = $request->keterangan ?? null;
            $layanan->id_user = Auth::user()->id_user;
            $layanan->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Layanan Document Gagal Disimpan' . $e->getMessage());
        }

        return redirect("layanan-dokumen/" . $request->id_layanan . '/detail')->with('success', 'Layanan Document Berhasil Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data['data'] = LayananDocument::where('id_layanan', $id)->get();
        return view('permohonan::layanan-dokumen.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('permohonan::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_layanan' => 'required',
            'nama_document' => 'required',
            'status' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $layanan = LayananDocument::findOrFail($id);
            $layanan->id_layanan = $request->id_layanan;
            $layanan->nama_document = $request->nama_document;
            $layanan->status = $request->status;
            $layanan->keterangan = $request->keterangan ?? null;
            $layanan->id_user = Auth::user()->id_user;
            $layanan->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Layanan Document Gagal Disimpan' . $e->getMessage());
        }

        return redirect("layanan-dokumen/" . $request->id_layanan . '/detail')->with('success', 'Layanan Document Berhasil Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $layanan = LayananDocument::findOrFail($id);
            $layanan->delete();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }
        return redirect()->back()->with('success', 'Data Layanan dihapus');
    }
}
