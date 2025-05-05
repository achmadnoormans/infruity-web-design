<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Department;
use Modules\Master\Entities\Position;
use Modules\Master\Entities\Staff;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Facades\Excel;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('master::staff.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['page_plugin_js'] = [
            'assets/plugins/custom/formrepeater/formrepeater.bundle.js',
        ];
        $data['data'] = null;
        return view('master::staff.create', $data);
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
            'staff_name' => 'required',
            'date_in' => 'nullable|date|date_format:Y-m-d',
            'gender' => 'nullable|in:male,female',
            'nik' => 'nullable',
            'contact' => [
                'nullable',
                'numeric',
                'digits_between:10,15',
                'regex:/^(?:\+62|62|08)[0-9]{8,13}$/'
            ],
            'email' => 'nullable|email',
            'department' => 'nullable|exists:department,id',
            'position' => 'nullable|exists:position,id',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $staff = new Staff();
            $staff->name = $request->staff_name;
            $staff->date_in = $request->date_in;
            $staff->gender = $request->gender;
            $staff->nik = $request->nik;
            $staff->contact = $request->contact;
            $staff->email = $request->email;
            $staff->department_id = $request->department;
            $staff->position_id = $request->position;
            $staff->position_id = $request->position;
            $staff->description = $request->description;
            $staff->status = $request->status ?? 'aktif';
            $staff->created_by = Auth::user()->id_user;
            $staff->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Pembuatan Staff gagal' . $e->getMessage());
        }

        return redirect('staff')->with('success', 'Pembuatan Staff berhasil');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('master::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['page_plugin_js'] = [
            'assets/plugins/custom/formrepeater/formrepeater.bundle.js',
        ];
        $data['data'] = Staff::findOrFail($id);
        $data['department'] = Department::where('id', $data['data']->department_id)->first();
        $data['position'] = Position::where('id', $data['data']->position_id)->first();
        return view('master::staff.create', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'staff_name' => 'required',
            'date_in' => 'nullable|date|date_format:Y-m-d',
            'gender' => 'nullable|in:male,female',
            'nik' => 'nullable',
            'contact' => [
                'nullable',
                'numeric',
                'digits_between:10,15',
                'regex:/^(?:\+62|62|08)[0-9]{8,13}$/'
            ],
            'email' => 'nullable|email',
            'department' => 'nullable|exists:department,id',
            'position' => 'nullable|exists:position,id',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $staff = Staff::findOrFail($id);
            $staff->name = $request->staff_name;
            $staff->date_in = $request->date_in;
            $staff->gender = $request->gender;
            $staff->nik = $request->nik;
            $staff->contact = $request->contact;
            $staff->email = $request->email;
            $staff->department_id = $request->department;
            $staff->position_id = $request->position;
            $staff->position_id = $request->position;
            $staff->description = strip_tags($request->description ?? '');
            $staff->status = $request->status ?? 'aktif';
            $staff->created_by = Auth::user()->id_user;
            $staff->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Update Staff gagal' . $e->getMessage());
        }

        return redirect('staff')->with('success', 'Update Staff berhasil');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $staff = Staff::findOrFail($id);
            $staff->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function get_data(Request $request)
    {
        $data = Staff::orderBy('name', 'asc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                $colors = ['warning', 'success', 'info', 'primary'];
                $color = $colors[$item->id % count($colors)];
                return '<div class="d-flex align-items-center">
                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                <a href="javascript:void(0)">
                                    <div class="symbol-label fs-3 bg-light-' . $color . ' text-' . $color . '">' . strtoupper(substr($item->name, 0, 1)) . '</div>
                                </a>
                            </div>
                            <div class="ms-5">
                                <a href="javascript:void(0)" class="text-gray-800 text-hover-primary fs-5 fw-bold">' . $item->name . '</a><br>
                                <span class="fs-7">' . $item->email . '</span>
                            </div>
                        </div>';
            })
            ->addColumn('date_in', function ($item) {
                return dateEnglish($item->date_in);
            })
            ->addColumn('position', function ($item) {
                return $item->position->name . '<br><span class="fs-7">' . $item->department->name . '</span>';
            })
            ->addColumn('action', function ($item) {
                return '
                    <div class="dropdown text-end">
                        <button class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary dropdown-toggle" 
                            type="button" 
                            id="dropdownMenuButton' . $item->id . '" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                            Actions
                            <i class="ki-outline ki-down fs-5 ms-1"></i>
                        </button>
            
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $item->id . '">
                            <li>
                                <a class="dropdown-item" href="' . route('staff.edit', $item->id) . '">
                                    Edit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteProduct(' . $item->id . ')">
                                    Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                ';
            })
            ->rawColumns(['name', 'action', 'position'])
            ->make(true);
    }
}
