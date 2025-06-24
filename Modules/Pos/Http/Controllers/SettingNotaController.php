<?php

namespace Modules\Pos\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pos\Entities\SettingNota;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SettingNotaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data['page_plugin_js'] = [
            'assets/plugins/custom/formrepeater/formrepeater.bundle.js',
        ];
        $data['data'] = SettingNota::first();
        return view('pos::setting-nota.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('pos::create');
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
        return view('pos::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('pos::edit');
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
        DB::beginTransaction();
        try {
            $userId = Auth::id(); // Ambil user sekali
            $settingNota = new SettingNota([
                'header' => $request->header,
                'footer' => $request->footer,
            ]);
            $settingNota->save();

            DB::commit();

            return redirect()->route('setting-nota.index')->with('success', 'Pembuatan Setting Nota berhasil');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Pembuatan Setting Nota gagal' . $e->getMessage());
        }
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
}
