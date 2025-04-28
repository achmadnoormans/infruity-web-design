<?php

namespace Modules\Permohonan\Http\Controllers;

use Auth;
use DB;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Permohonan\Entities\Layanan;

class LayananController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data['data'] = Layanan::paginate(10);
        return view('permohonan::layanan.index', $data);
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
            'nm_layanan' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $layanan = new Layanan();
            $layanan->nm_layanan = $request->nm_layanan;
            $layanan->id_user = Auth::user()->id_user;
            $layanan->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Layanan Gagal Disimpan' . $e->getMessage());
        }

        return redirect("layanan")->with('success', 'Layanan Berhasil Disimpan');
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
            'nm_layanan' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $layanan = Layanan::findOrFail($id);
            $layanan->nm_layanan = $request->nm_layanan;
            $layanan->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Layanan Gagal Diupdate' . $e->getMessage());
        }

        return redirect("layanan")->with('success', 'Layanan Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $layanan = Layanan::findOrFail($id);
            $layanan->delete();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }
        return redirect()->back()->with('success', 'Data Layanan dihapus');
    }
}
