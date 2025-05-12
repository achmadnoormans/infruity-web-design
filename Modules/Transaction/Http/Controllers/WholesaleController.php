<?php

namespace Modules\Transaction\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\ProductCategory;
use Modules\Master\Entities\Supplier;
use Modules\Master\Entities\Product;
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
        // $data['products'] = collect()
        // ->merge(
        //     ProductCategory::all()->map(function ($item) {
        //         return (object) [
        //             'id' => $item->id,
        //             'name' => $item->name,
        //             'type' => 'category',
        //         ];
        //     })
        // )
        // ->merge(
        //     Product::where('direct_stock', 1)->get()->map(function ($item) {
        //         return (object) [
        //             'id' => $item->id,
        //             'name' => $item->name,
        //             'type' => 'product',
        //         ];
        //     })
        // )
        // ->values(); // optional: reset index numerik

        $wholsale = new Wholesale();
        $wholsale->order_date = date('Y-m-d');
        $wholsale->status = 'draft';
        $wholsale->created_by = Auth::user()->id_user;
        $wholsale->save();

        $data['products'] = Product::whereNull('parent_id')->get(); // optional: reset index numerik
        $data['suppliers'] = Supplier::all();
        return redirect('wholesale/'. $wholsale->id . '/edit')->with('success', 'Pembuatan Data Kulak berhasil');
        // return view('transaction::wholesale.create', $data);
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
            $wholesale->order_number = Wholesale::getOrderNumber();
            $wholesale->order_date = $request->order_date;
            $wholesale->description = $request->description;
            $wholesale->status = 'processing';
            $wholesale->created_by = Auth::user()->id_user;
            $wholesale->save();

            $wholesaleId = $wholesale->id;
            foreach ($request->products as $product) {
                $wholesaleDetail = new WholesaleProduct();
                $wholesaleDetail->wholesale_id = $wholesaleId;
                $wholesaleDetail->quantity = $product['qty'];
                $wholesaleDetail->price = $product['price'];
                $wholesaleDetail->total_price = $product['price'] * $product['qty'];
                if ($product['type'] == 'product') {    
                    $wholesaleDetail->product_id = $product['id'];
                } else {
                    $wholesaleDetail->category_id = $product['id'];
                }
                $wholesaleDetail->supplier_id = $product['supplier_id'];
                // dd($wholesaleDetail);
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
        return redirect()->back()->withInput()
                ->with('error', 'Halaman Belum dibuat');
        // return view('transaction::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['products'] = Product::whereNull('parent_id')->get(); // optional: reset index numerik
        $data['suppliers'] = Supplier::all();
        $data['data'] = Wholesale::findOrFail($id);
        // $data['selectedProducts'] = WholesaleProduct::with('category', 'supplier', 'product')
        //     ->where('wholesale_id', $id)
        //     ->get()
        //     ->map(function ($item) {
        //         return [
        //             'id' => $item->category_id != 0? $item->category_id : $item->product_id,
        //             'name' => $item->category_id != 0 ? $item->category->name : $item->product->name,
        //             'price' => number_format($item->price, 0, ',', '.'),
        //             'total_price' => number_format($item->total_price, 0, ',', '.'),
        //             'qty' => $item->quantity,
        //             'supplier_id' => $item->supplier_id,
        //             'supplier_name' => $item->supplier->name,
        //             'type' => $item->product_id != null ? 'product' : 'category',
        //         ];
        //     });
            // dd($data);
        return view('transaction::wholesale.create', $data);
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
                $wholesaleDetail->quantity = $product['qty'];
                $wholesaleDetail->price = $product['price'];
                $wholesaleDetail->total_price = $product['price'] * $product['qty'];
                if ($product['type'] == 'product') {    
                    $wholesaleDetail->product_id = $product['id'];
                } else {
                    $wholesaleDetail->category_id = $product['id'];
                }
                $wholesaleDetail->supplier_id = $product['supplier_id'];
                // dd($wholesaleDetail);
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

    public function receive_productOld($id)
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

    public function receive_product(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $wholesale = Wholesale::findOrFail($id);
            $wholesale->status = 'complete';
            $wholesale->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diproses.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses data: ' . $e->getMessage()
            ], 500);
        }
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

    public function get_product(Request $request, $id)
    {
        $data = WholesaleProduct::where('wholesale_id', $id)->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                return $item->product->name;
            })
            ->addColumn('supplier', function ($item) {
                return $item->supplier->name;
            })
            ->addColumn('price', function ($item) {
                return 'Rp.' . toNumber($item->price);
            })
            ->addColumn('total', function ($item) {
                return 'Rp.' . toNumber($item->total_price);
            })
            ->addColumn('action', function ($item) {
                $html = '';
                $html .= '
                <div class="d-flex flex-row gap-1">
                    <button type="button" class="btn btn-sm btn-warning" onclick="editProduct(' . $item->id . ')">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteProduct(' . $item->id . ')">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                ';
                return $html;
            })
            ->rawColumns(['name', 'action'])
            ->make(true);
    }

    public function save_product(Request $request)
    {
        $validated = $request->validate([
            'wholesale_id' =>'required|exists:wholesale,id',
            'id' =>'required|exists:products,id',
            'supplier_id' =>'required|exists:supplier,id',
            'qty' =>'required',
            'price' =>'required',
        ]);

        try {
            DB::beginTransaction();
            $wholesaleProduct = new WholesaleProduct();
            $wholesaleProduct->wholesale_id = $validated['wholesale_id'];
            $wholesaleProduct->product_id = $validated['id'];
            $wholesaleProduct->supplier_id = $validated['supplier_id'];
            $wholesaleProduct->quantity = $validated['qty'];
            $wholesaleProduct->price = $validated['price'];
            $wholesaleProduct->total_price = $validated['price'] * $validated['qty'];
            $wholesaleProduct->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Product gagal disimpan.',
                'data' => $wholesaleProduct
            ], 404);
        }

        // Kirim response JSON
        return response()->json([
            'message' => 'Product berhasil disimpan.',
            'data' => $wholesaleProduct
        ], 201);
    }

    public function delete_product(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $wholesaleProduct = WholesaleProduct::findOrFail($id);
            $wholesaleProduct->delete();
            DB::commit();
            return response()->json([
               'message' => 'Product berhasil dihapus.',
            ], 201);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
               'message' => 'Product gagal dihapus.',
            ], 404);
        }
    }

    public function edit_product(Request $request, $id)
    {
        $data = WholesaleProduct::findOrFail($id);
        return response()->json([
            'data' => $data
        ], 201);
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
                                    <div class="symbol-label fs-3 bg-light-' . $color . ' text-' . $color . '">' . strtoupper(substr($item->order_number, 0, 1)) . '</div>
                                </a>
                            </div>
                            <div class="ms-5">
                                <a href="javascript:void(0)" class="text-gray-800 text-hover-primary fs-5 fw-bold">#' . $item->order_number . '</a>
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
            ->addColumn('status_raw', function ($item) {
                return $item->status;
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
                                <a class="dropdown-item text-success" href="javascript:void(0)" onclick="receiveProduct(' . $item->id . ')">
                                    Terima Barang
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
