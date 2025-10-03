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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

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
            'birth_of_date' => 'nullable|date|date_format:Y-m-d',
            'gender' => 'nullable|in:male,female',
            'province' => 'nullable|exists:reg_provinces,id',
            'city' => 'nullable|exists:reg_regencies,id',
            'district' => 'nullable|exists:reg_districts,id',
            'village' => 'nullable|exists:reg_villages,id',
            'address' => 'nullable',
            'phone' => [
                'nullable',
                'numeric',
                'digits_between:10,15',
                'regex:/^(?:\+62|62|08)[0-9]{8,13}$/',
                'unique:customer,whatsapp'
            ],
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
        $customer = Customer::findOrFail($id);
        $data = [
            'data' => $customer,
            'province' => Region::getProvince($customer->province),
            'city' => Region::getCity($customer->city),
            'district' => Region::getDistrict($customer->district),
            'village' => Region::getVillage($customer->village),
        ];
        // dd($data);
        return view('master::customer.show', $data);
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
            'birth_of_date' => 'nullable|date|date_format:Y-m-d',
            'gender' => 'nullable|in:male,female',
            'province' => 'nullable|exists:reg_provinces,id',
            'city' => 'nullable|exists:reg_regencies,id',
            'district' => 'nullable|exists:reg_districts,id',
            'village' => 'nullable|exists:reg_villages,id',
            'address' => 'nullable',
            'phone' => [
                'nullable',
                'numeric',
                'digits_between:10,15',
                'regex:/^(?:\+62|62|08)[0-9]{8,13}$/',
                'unique:customer,whatsapp,' . $id,
            ],
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

    public function storeCustomer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();
            $customer = new Customer();
            $customer->name = $request->name;
            $customer->code = Customer::getCustomerNumber();
            $customer->address = $request->address;
            $customer->whatsapp = $request->phone;
            $customer->created_by = Auth::user()->id_user;
            $customer->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'address' => $customer->address
                ]
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data: ' . $e->getMessage()
            ], 500);
        }


    }

    public function get_data(Request $request)
    {
        $data = Customer::orderBy('name', 'asc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                return '<div class="d-flex align-items-center">
                            <div class="ms-5">
                                <a href="javascript:void(0)" class="text-gray-800 text-hover-primary fs-5 fw-bold">' . $item->name . '</a><br>
                                <span class="fs-7">' . $item->code . '</span><br>
                                <span class="badge badge-light-success fs-7">' . $item->whatsapp . '</span>
                            </div>
                        </div>';
            })
            ->addColumn('birth_of_date', function ($item) {
                return '<span class="badge badge-light-danger">' . \Carbon\Carbon::parse($item->birth_of_date)->diffInYears() . '</span> Thn';
            })
            ->addColumn('action', function ($item) {
                $html = '';
                if ($item->status != 'complete') {
                    $html .= '
                    <div class="dropstart">
                        <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">                        
                            <li>
                                <a class="dropdown-item" href="' . route('customers.show', $item->id) . '">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="' . route('customers.edit', $item->id) . '">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-primary d-flex justify-content-center" href="javascript:void(0)" onclick="deleteProduct(' . $item->id . ')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    ';
                }
                return $html;
            })
            ->rawColumns(['name', 'action', 'birth_of_date'])
            ->make(true);
    }

    public function getCustomer(Request $request)
    {
        $search = $request->get('search');

        $customer = Customer::where('name', 'like', '%' . $search . '%')
            ->leftjoin('vw_customer_tier', 'vw_customer_tier.customer_id', '=', 'customer.id')
            ->orWhere('whatsapp', 'like', '%' . $search . '%')
            ->select('*')
            ->limit(10)
            ->get();

        $data = [];
        foreach ($customer as $item) {
            $data[] = [
                'id' => $item->id,
                'name' => $item->name,
                'address' => $item->address,
                'whatsapp' => $item->whatsapp,
                'tier_name' => $item->tier_name,
                'tier_id' => $item->tier_id,
                'tier_style' => $item->tier_style,
                'minimal_purchase' => $item->minimal_purchase ?? 0, // Pastikan minimal_purchase ada di data
                'voucher' => $item->voucher ?? 0,
                'discount' => $item->discount ?? 0,
            ];
        }

        return response()->json($data);
    }
}
