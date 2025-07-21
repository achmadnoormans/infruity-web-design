<?php

namespace Modules\Crm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Crm\Entities\PointSchedule;
use Modules\Crm\Entities\PointFrequency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PointScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data['data'] = PointSchedule::first();
        $data['frequencies'] = PointFrequency::all();
        return view('crm::point-schedule.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('crm::create');
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
        return view('crm::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('crm::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $settingExp = PointSchedule::findOrFail($id);
            $settingExp->start_date = $request->start_date;
            $settingExp->end_date = $request->end_date;
            $settingExp->frequency = $request->frequency;
            $settingExp->break = $request->break;
            $settingExp->updated_by = Auth::id();
            $settingExp->save();

            DB::commit();

            return redirect()->route('point-schedule.index')->with('success', 'Atur Jadwal Reset Automatis berhasil');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Pembuatan Atur Jadwal Reset Automatis gagal: ' . $e->getMessage());
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
