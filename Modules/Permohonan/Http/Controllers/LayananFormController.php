<?php

namespace Modules\Permohonan\Http\Controllers;

use Auth;
use DB;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Permohonan\Entities\Layanan;
use Modules\Permohonan\Entities\LayananForm;

class LayananFormController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data['data'] = Layanan::paginate(10);
        return view('permohonan::layanan-form.index', $data);
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
            'nama_form' => 'required',
            'type' => 'required',
            'status' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $layanan = new LayananForm();
            $layanan->id_layanan = $request->id_layanan;
            $layanan->nama_form = $request->nama_form;
            $layanan->type = $request->type;
            $layanan->status = $request->status;
            $layanan->id_user = Auth::user()->id_user;
            $layanan->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Layanan Form Gagal Disimpan' . $e->getMessage());
        }

        return redirect("layanan-form/" . $request->id_layanan . '/detail')->with('success', 'Layanan Form Berhasil Disimpan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data['data'] = LayananForm::where('id_layanan', $id)->get();
        return view('permohonan::layanan-form.show', $data);
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
            'nama_form' => 'required',
            'type' => 'required',
            'status' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $layanan = LayananForm::findOrFail($id);
            $layanan->id_layanan = $request->id_layanan;
            $layanan->nama_form = $request->nama_form;
            $layanan->type = $request->type;
            $layanan->status = $request->status;
            $layanan->id_user = Auth::user()->id_user;
            $layanan->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Layanan Form Gagal Disimpan' . $e->getMessage());
        }

        return redirect("layanan-form/" . $request->id_layanan . '/detail')->with('success', 'Layanan Form Berhasil Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $layanan = LayananForm::findOrFail($id);
            $layanan->update(['deleted_at' => now()]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }
        return redirect()->back()->with('success', 'Data Layanan dihapus');
    }
}
