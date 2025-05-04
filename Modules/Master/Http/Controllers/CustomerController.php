<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Customer;
use Modules\Master\Entities\Region;
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

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('master::customer.index');
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
        $data['product_units'] = ProductUnit::all();
        $data['data'] = null;
        $data['customerNumber'] = Customer::getCustomerNumber(); // ['code', 'number']
        return view('master::customer.create', $data);
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
            'customer_name' => 'required',
            'birth_of_date' =>'nullable|date|date_format:Y-m-d',
            'gender' =>'nullable|in:male,female',
            'province' => 'nullable|exists:reg_provinces,id',
            'city' => 'nullable|exists:reg_regencies,id',
            'district' => 'nullable|exists:reg_districts,id',
            'village' => 'nullable|exists:reg_villages,id',
            'address' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $customer = new Customer();
            $customer->name = $request->customer_name;
            $customer->code = Customer::getCustomerNumber();
            $customer->birth_of_date = $request->birth_of_date;
            $customer->gender = $request->gender;
            $customer->province = $request->province;
            $customer->city = $request->city;
            $customer->district = $request->district;
            $customer->village = $request->village;
            $customer->address = $request->address;
            $customer->whatsapp = $request->phone;
            $customer->email = $request->email;
            $customer->created_by = Auth::user()->id_user;
            $customer->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Pembuatan Customer gagal' . $e->getMessage());
        }

        return redirect('customers')->with('success', 'Pembuatan Customer berhasil');
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
        
        $customer = Customer::findOrFail($id);
        $data = [
            'data' => $customer,
            'province' => Region::getProvince($customer->province),
            'city' => Region::getCity($customer->city),
            'district' => Region::getDistrict($customer->district),
            'village' => Region::getVillage($customer->village),
        ];
        $data['page_plugin_js'] = [
            'assets/plugins/custom/formrepeater/formrepeater.bundle.js',
        ];
        // dd($data);
        return view('master::customer.create', $data);
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
            'customer_name' => 'required',
            'birth_of_date' =>'nullable|date|date_format:Y-m-d',
            'gender' =>'nullable|in:male,female',
            'province' => 'nullable|exists:reg_provinces,id',
            'city' => 'nullable|exists:reg_regencies,id',
            'district' => 'nullable|exists:reg_districts,id',
            'village' => 'nullable|exists:reg_villages,id',
            'address' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $customer = Customer::findOrFail($id);
            $customer->name = $request->customer_name;
            $customer->birth_of_date = $request->birth_of_date;
            $customer->gender = $request->gender;
            $customer->province = $request->province;
            $customer->city = $request->city;
            $customer->district = $request->district;
            $customer->village = $request->village;
            $customer->address = $request->address;
            $customer->whatsapp = $request->phone;
            $customer->email = $request->email;
            $customer->updated_by = Auth::user()->id_user;
            $customer->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Update Customer gagal' . $e->getMessage());
        }

        return redirect('customers')->with('success', 'Update Customer berhasil');
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
            $customer = Customer::findOrFail($id);
            $customer->delete();
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
        $data = Customer::orderBy('name', 'asc')->get();
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
                                <span class="fs-7">' . $item->code . '</span>
                            </div>
                        </div>';
            })
            ->addColumn('birth_of_date', function ($item) {
                return dateEnglish($item->birth_of_date);
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
                                <a class="dropdown-item" href="' . route('customers.edit', $item->id) . '">
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
            ->rawColumns(['name', 'action'])
            ->make(true);
    }
}
