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
    use \App\Traits\HasAccessControl;

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if ($denied = $this->requireAccess('setting-nota.index')) {
            return $denied;
        }

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
        if ($denied = $this->requireAccess('setting-nota.create')) {
            return $denied;
        }

        return view('pos::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if ($denied = $this->requireAccess('setting-nota.store')) {
            return $denied;
        }

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
        if ($denied = $this->requireAccess('setting-nota.edit')) {
            return $denied;
        }

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
        if ($denied = $this->requireAccess('setting-nota.update')) {
            return $denied;
        }

        // dd($request->all());
        DB::beginTransaction();
        try {
            $settingNota = SettingNota::findOrFail($id);
            $settingNota->header = $request->header;
            $settingNota->footer = $request->footer;
            $settingNota->is_using_logo = $request->hasFile('avatar') ? true : false;
            $settingNota->brand_name = $request->brand_name;
            $settingNota->brand_address = $request->brand_address;
            $settingNota->brand_social_media = $request->brand_social_media;
            $settingNota->brand_phone = $request->brand_phone;
            $settingNota->brand_greeting = $request->brand_greeting;
            $settingNota->note = $request->note;
            $settingNota->is_using_cashier = $request->is_using_cashier ? true : false;
            $settingNota->is_using_customer = $request->is_using_customer ? true : false;
            $settingNota->is_using_date = $request->is_using_date ? true : false;
            $settingNota->is_using_invoice_number = $request->is_using_invoice_number ? true : false;
            $settingNota->updated_by = Auth::id();
            
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('logo', 'public');
                $settingNota->logo = $path;
                // $product->save();
            }
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
        if ($denied = $this->requireAccess('setting-nota.destroy')) {
            return $denied;
        }

        //
    }

    /**
     * View the receipt template.
     * @return Renderable
     */
    public function viewReceipt()
    {
        if ($denied = $this->requireAccess('setting-nota.view-receipt')) {
            return $denied;
        }

        $data['setting'] = SettingNota::first();
        return view('pos::setting-nota.view', $data);
    }
}
