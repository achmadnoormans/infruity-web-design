<?php

namespace Modules\Permohonan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Permohonan\Entities\Permohonan;
use Modules\Permohonan\Entities\PermohonanSurat;
use Modules\Permohonan\Entities\PenguranganIptSurat;

class SelectController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('permohonan::index');
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
        //
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
        //
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
     * Search the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function search(Request $request, $id = null)
    {
        $search = $request->get('q');
        $permohonan = Permohonan::where('no_permohonan', 'LIKE', "%$search%")
            ->select('id', 'no_permohonan')
            ->get();

        $results = [];
        foreach ($permohonan as $permohonan) {
            $results[] = ['id' => $permohonan->id, 'text' => $permohonan->no_permohonan];
        }

        return response()->json($results);
    }

    /**
     * Search the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function search_surat(Request $request, $id = null)
    {
        $search = $request->get('q');
        $surat = PermohonanSurat::where('nomer_surat', 'LIKE', "%$search%")
            ->select('id', 'nomer_surat')
            ->get();

        if ($id == 'sk') {
            $surat = PenguranganIptSurat::where('nomer_surat', 'LIKE', "%$search%")
                ->select('id', 'nomer_surat')
                ->get();
        }

        $results = [];
        foreach ($surat as $surat) {
            $results[] = ['id' => $surat->id, 'text' => $surat->nomer_surat];
        }

        return response()->json($results);
    }

    /**
     * Search the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function search_persil(Request $request, $id = null)
    {
        $search = $request->get('q');
        $permohonan = Permohonan::where('alamat_persil', 'LIKE', "%$search%")
            ->select('alamat_persil')
            ->get();

        $results = [];
        foreach ($permohonan as $permohonan) {
            $results[] = ['id' => $permohonan->alamat_persil, 'text' => $permohonan->alamat_persil];
        }

        return response()->json($results);
    }

    /**
     * Search the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function search_nama_pemohon(Request $request, $id = null)
    {
        $search = $request->get('q');
        $permohonan = Permohonan::where('nama_pemohon', 'LIKE', "%$search%")
            ->select('nama_pemohon')
            ->get();

        $results = [];
        foreach ($permohonan as $permohonan) {
            $results[] = ['id' => $permohonan->nama_pemohon, 'text' => $permohonan->nama_pemohon];
        }

        return response()->json($results);
    }
}
