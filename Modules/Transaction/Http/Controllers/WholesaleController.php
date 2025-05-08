<?php

namespace Modules\Transaction\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Product;
use Modules\Master\Entities\Supplier;
use Modules\Transaction\Entities\Wholesale;
use Modules\Transaction\Entities\WholesaleProduct;
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

class WholesaleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('transaction::wholesale.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['products'] = Product::all();
        $data['suppliers'] = Supplier::all();
        $data['data'] = null;
        return view('transaction::wholesale.create', $data);
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
            'supplier_id' => 'required|exists:supplier,id',
            'order_date' => 'required',
            'description' => 'nullable|string|max:255',
            'products' => 'required|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $wholesale = new Wholesale();
            $wholesale->supplier_id = $request->supplier_id;
            $wholesale->order_date = $request->order_date;
            $wholesale->description = $request->description;
            $wholesale->status = 'processing';
            $wholesale->created_by = Auth::user()->id_user;
            $wholesale->save();

            $wholesaleId = $wholesale->id;
            foreach ($request->products as $product) {
                $wholesaleDetail = new WholesaleProduct();
                $wholesaleDetail->wholesale_id = $wholesaleId;
                $wholesaleDetail->product_id = $product['id'];
                $wholesaleDetail->quantity = $product['qty'];
                $wholesaleDetail->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Pembuatan Data Kulak gagal' . $e->getMessage());
        }

        return redirect('wholesale')->with('success', 'Pembuatan Data Kulak berhasil');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('transaction::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['products'] = Product::all();
        $data['suppliers'] = Supplier::all();
        $data['data'] = Wholesale::findOrFail($id);
        $data['selectedProducts'] = WholesaleProduct::with('product')
            ->where('wholesale_id', $id)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => number_format($item->product->price, 0, ',', '.'),
                    // 'image' => asset('storage/' . $item->product->image),
                    'image' => null,
                    'qty' => $item->quantity,
                ];
            });
        return view('transaction::wholesale.edit', $data);
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
        // Validasi input
        $request->validate([
            'supplier_id' => 'required',
            'order_date' => 'required|date',
            'products' => 'required|array',
        ]);

        try {
            DB::beginTransaction();
            $wholesale = Wholesale::find($id);
            $wholesale->supplier_id = $request->supplier_id;
            $wholesale->order_date = $request->order_date;
            $wholesale->description = $request->description;
            $wholesale->save();

            // Update atau hapus produk wholesale
            $wholesaleId = $wholesale->id;
            WholesaleProduct::where('wholesale_id', $wholesaleId)->delete();
            foreach ($request->products as $product) {
                $wholesaleDetail = new WholesaleProduct();
                $wholesaleDetail->wholesale_id = $wholesaleId;
                $wholesaleDetail->product_id = $product['id'];
                $wholesaleDetail->quantity = $product['quantity'];
                $wholesaleDetail->save();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Pembuatan Data Kulak gagal' . $e->getMessage());
        }
        return redirect('wholesale')->with('success', 'Update Data Kulak berhasil');
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
            $wholesale = Wholesale::findOrFail($id);
            $wholesale->delete();
            WholesaleProduct::where('wholesale_id', $id)->delete();
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

    public function receive_product($id)
    {
        $data['data'] = Wholesale::findOrFail($id);
        $data['selectedProducts'] = WholesaleProduct::with('product')
            ->where('wholesale_id', $id)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->product->name,
                    'sku' => $item->product->sku,
                    'unit' => $item->product->unit->abbreviation,
                    'price' => number_format($item->product->price, 0, ',', '.'),
                    'image' => asset('storage/' . $item->product->image),
                    'qty' => $item->quantity,
                ];
            });
        // dd($data);
        return view('transaction::wholesale.receive_product', $data);
    }

    public function save_receive(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'wholesale_id' => 'required|exists:wholesale,id',
            'products' => 'required|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $wholesale = Wholesale::findOrFail($request->wholesale_id);
            $wholesale->status = 'complete';
            $wholesale->save();

            foreach ($request->products as $key => $product) {
                $wholesaleDetail = WholesaleProduct::findOrFail($key);
                $wholesaleDetail->quantity = $product['quantity'];
                $wholesaleDetail->hpp = $product['price'];
                $wholesaleDetail->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Pembuatan Data Kulak gagal' . $e->getMessage());
        }

        return redirect('wholesale')->with('success', 'Pembuatan Data Kulak berhasil');
    }

    public function get_data(Request $request)
    {
        $data = Wholesale::getData();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                $colors = ['warning', 'success', 'info', 'primary'];
                $color = $colors[$item->id % count($colors)];
                return '<div class="d-flex align-items-center">
                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                <a href="javascript:void(0)">
                                    <div class="symbol-label fs-3 bg-light-' . $color . ' text-' . $color . '">' . strtoupper(substr($item->supplier_name, 0, 1)) . '</div>
                                </a>
                            </div>
                            <div class="ms-5">
                                <a href="javascript:void(0)" class="text-gray-800 text-hover-primary fs-5 fw-bold">' . $item->supplier_name . '</a><br>
                                <span class="fs-7">' . $item->whatsapp . '</span>
                            </div>
                        </div>';
            })
            ->addColumn('order_date', function ($item) {
                return dateindo($item->order_date);
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 'processing') {
                    return '<span class="badge badge-light-primary">Processing</span>';
                } elseif ($item->status == 'complete') {
                    return '<span class="badge badge-light-success">Complete</span>';
                } else {
                    return '<span class="badge badge-light-danger">Unknown</span>';
                }
            })
            ->addColumn('action', function ($item) {
                $html = '';
                if ($item->status != 'complete') {
                    $html .= '
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
                                <a class="dropdown-item" href="' . route('wholesale.edit', $item->id) . '">
                                    Edit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="' . route('wholesale.receive_product', $item->id) . '">
                                    Receive Product
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
                }
                return $html;
            })
            ->rawColumns(['name', 'action', 'status', 'address'])
            ->make(true);
    }
}
