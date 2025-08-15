<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Product;
use Modules\Master\Entities\ProductCategory;
use Modules\Master\Entities\ProductUnit;
use Modules\Master\Entities\ProductChild;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('master::products.index');
    }

    public function get_stock()
    {
        $data['category'] = ProductCategory::all();
        return view('master::products.stock', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['product_units'] = ProductUnit::all();
        $data['data'] = null;
        return view('master::products.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // dd($request->all(), $request->file('avatar'));
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|unique:products,name',
            'price' => 'required',
            'product_unit_id' => 'required|exists:product_units,id',
            'status' => 'required',
            // 'category_id' => 'required|exists:products_category,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $product = new Product();
            $product->name = $request->product_name;
            $product->category_id = $request->category_id;
            $product->description = strip_tags($request->description ?? '');
            $product->price = $request->price ?? '';
            $product->product_unit = $request->product_unit_id ?? '';
            $product->stock = $request->stock ?? 0;
            $product->limit = $request->limit ?? 0;
            $product->handling = $request->handling ?? '';
            $product->sku = $request->sku ?? '';
            $product->barcode = $request->barcode ?? '';
            $product->status = $request->status ?? '';
            $product->tipe = $request->tipe ?? 'product';
            $product->created_by = Auth::user()->id_user;

            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('products', 'public');
                $product->image = $path;
                // $product->save();
            }

            $product->save();

            $productId = $product->id;
            if (isset($request->variant)) {
                foreach ($request->variant['id'] as $key => $value) {
                    if (is_numeric($value)) {
                        $variant = Product::find($value);
                        $variant->is_variant = 1;
                        $variant->save();
                    } else {
                        $variant = new Product();
                        $variant->parent_id = $productId;
                        $variant->name = $value;
                        $variant->price = $request->variant['price'][$key];
                        $variant->category_id = $product->category_id;
                        $variant->product_unit = $product->product_unit;
                        $variant->stock = $product->stock;
                        $variant->limit = $product->limit;
                        $variant->handling = $product->handling;
                        $variant->is_variant = 1;
                        $variant->created_by = Auth::user()->id_user;
                        $variant->description = strip_tags($request->description ?? '');
                        $variant->save();
                    }

                    $child = new ProductChild();
                    $child->product_id = $variant->id;
                    $child->parent_id = $productId;
                    $child->save();
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Pembuatan Product gagal' . $e->getMessage());
        }

        return redirect('products')->with('success', 'Pembuatan Product berhasil');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $data = [
            'data' => $product,
            'product_units' => ProductUnit::all(),
        ];
        // dd($data);
        return view('master::products.create', $data);
    }

    public function show_stock($id)
    {
        $data['data'] = Product::findOrFail($id);
        return view('master::products.stock-show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $data = [
            'data' => $product,
            'product_units' => ProductUnit::all(),
        ];
        if (isset($product->category_id)) {
            $data['category'] = ProductCategory::findOrFail($product->category_id);
        } else {
            $data['category'] = null;
        }
        $data['variant'] = ProductChild::with('product')->where('parent_id', $id)->get();
        // dd($data);
        return view('master::products.create', $data);
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
            'product_name' => 'required|unique:products,name,' . $id,
            'price' => 'required',
            'product_unit_id' => 'required|exists:product_units,id',
            'status' => 'required',
            'category_id' => 'nullable|exists:products_category,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $product = Product::findOrFail($id);
            if ($request->hasFile('avatar')) {
                // Hapus gambar lama jika ada
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $path = $request->file('avatar')->store('products', 'public');
                $product->image = $path;
            }
            $product->name = $request->product_name;
            $product->category_id = $request->category_id;
            $product->description = strip_tags($request->description ?? '');
            $product->price = $request->price ?? '';
            $product->product_unit = $request->product_unit_id ?? '';
            $product->stock = $request->stock ?? 0;
            $product->limit = $request->limit ?? 0;
            $product->handling = $request->handling ?? '';
            $product->sku = $request->sku ?? '';
            $product->barcode = $request->barcode ?? '';
            $product->status = $request->status ?? '';
            $product->tipe = $request->tipe ?? 'product';
            $product->created_by = Auth::user()->id_user;

            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('products', 'public');
                $product->image = $path;
                $product->save();
            }

            // Hapus semua varian yang ada
            ProductChild::where('parent_id', $id)->delete();
            $productId = $id;
            // Simpan varian baru
            if (isset($request->variant)) {
                foreach ($request->variant['id'] as $key => $value) {
                    if (is_numeric($value)) {
                        $variant = Product::find($value);
                        $variant->is_variant = 1;
                        $variant->save();
                    } else {
                        $variant = new Product();
                        $variant->parent_id = $productId;
                        $variant->name = $value;
                        $variant->price = $request->variant['price'][$key];
                        $variant->category_id = $product->category_id;
                        $variant->product_unit = $product->product_unit;
                        $variant->stock = $product->stock;
                        $variant->limit = $product->limit;
                        $variant->handling = $product->handling;
                        $variant->is_variant = 1;
                        $variant->created_by = Auth::user()->id_user;
                        $variant->description = strip_tags($request->description ?? '');
                        $variant->save();
                    }

                    $child = new ProductChild();
                    $child->product_id = $variant->id;
                    $child->parent_id = $productId;
                    $child->save();
                }
            }

            $product->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Update Product gagal' . $e->getMessage());
        }

        return redirect('products')->with('success', 'Update Product berhasil');
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
            $product = Product::findOrFail($id);
            $child = Product::where('parent_id', $id)->get();
            if ($child->count() > 0) {
                foreach ($child as $item) {
                    $item->delete();
                }
            }
            $product->delete();
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

    public function updatePrice(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();
            $product = Product::findOrFail($id);
            $product->price = $request->price;
            $product->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Harga berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui harga: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getVariant(Request $request)
    {
        $productId = $request->product_id;

        $variants = Product::where('parent_id', $productId);

        return DataTables::of($variants)
            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-sm btn-light-primary edit-variant variant" type="button" 
                        data-id="' . $row->id . '" 
                        data-name="' . $row->name . '" 
                        data-price="' . $row->price . '">
                    Edit
                </button>
                <button class="btn btn-sm btn-light-danger delete-variant variant" type="button"
                        data-id="' . $row->id . '">
                    Hapus
                </button>
            ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function storeVariant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_id' => 'required|exists:products,id',
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $parentProduct = Product::findOrFail($request->parent_id);
            DB::beginTransaction();
            $product = new Product();
            $product->parent_id = $request->parent_id;
            $product->name = $request->product_name;
            $product->price = $request->price;
            $product->category_id = $parentProduct->category_id;
            $product->product_unit = $parentProduct->product_unit;
            $product->stock = $parentProduct->stock;
            $product->limit = $parentProduct->limit;
            $product->handling = $parentProduct->handling;
            $product->created_by = Auth::user()->id_user;
            $product->description = $parentProduct->description;
            $product->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Variant berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan variant: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateVariant(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_name' => 'required|string|max:255',
                'price' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            DB::beginTransaction();
            $variant = Product::findOrFail($id);
            $variant->name = $request->product_name;
            $variant->price = $request->price;
            $variant->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Gagal memperbarui variant: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Updated']);
    }

    public function destroyVariant($id)
    {
        try {
            DB::beginTransaction();
            $variant = Product::findOrFail($id);
            $variant->delete();
            DB::commit();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus variant: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Deleted']);
    }

    public function getProductReceipt(Request $request)
    {
        $search = $request->input('search', '');
        $query = Product::where('status', 'receipt')
            ->where('name', 'like', '%' . $search . '%')
            ->where('status', 'receipt')
            ->select('id', 'name')
            ->get();

        return response()->json($query);
    }

    public function getProduct(Request $request)
    {
        $search = $request->input('search', '');
        $query = Product::select('id', 'name')
            ->where('name', 'like', '%' . $search . '%')
            ->get();

        return response()->json($query);
    }

    public function listProduct(Request $request)
    {
        $search = $request->input('term', '');
        $query = Product::with('category', 'get_stock', 'unit')
            ->where('name', 'like', '%' . $search . '%');

        if ($request->type == 'kemasan') {
            $query = $query->where('tipe', 'kemasan');
        }

        $query = $query->get();

        return response()->json($query);
    }

    /**
     * Get data for DataTables
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function get_data(Request $request)
    {
        $searchValue = $request->input('searchValue'); // Ambil nilai pencarian
        if (empty($searchValue)) {
            return DataTables::of([])->make(true); // Kembalikan tabel kosong jika tidak ada pencarian
        }
        $query = Product::query()
            ->with('category')
            ->where('name', 'like', '%' . $searchValue . '%');

        $data = $query->get();
        // $data = Product::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($product) {
                $html = '
                    <div class="d-flex align-items-center">';
                if (isset($product->image)) {
                    $url = asset('storage/' . $product->image);
                    $html .= '<img src="' . $url . '" alt="Product Image" width="50">';
                } else {
                    $html .= '<a href="javascript:void(0)" class="symbol symbol-50px">
                            <span class="symbol-label" style="background-image:url(assets/media/svg/files/blank-image.svg);"></span>
                        </a>';
                }
                $html .= '<div class="ms-5">
                            <a href="' . url('products') . '/' . $product->id . '/show' . '" class="text-gray-800 text-hover-primary fs-5 fw-bold" 
                               data-kt-ecommerce-product-filter="product_name">
                                ' . e($product->name) . '
                            </a>
                        </div>
                    </div>
                ';
                return $html;
            })
            ->addColumn('price', function ($product) {
                return '<span class="badge badge-light-primary editable-price" data-id="' . $product->id . '" data-value="' . $product->price . '">Rp' . toNumber($product->price) . '</span>';
            })
            ->addColumn('category', function ($product) {
                return $product->category->name ?? '-';
            })
            ->addColumn('unit', function ($product) {
                return $product->unit->abbreviation ?? '-';
            })
            ->addColumn('status', function ($product) {
                $html = '';
                if ($product->status == 'receipt') {
                    $html .= '<span class="badge badge-light-success">Menggunakan Resep</span>';
                } elseif ($product->status == 'no-receipt') {
                    $html .= '<span class="badge badge-light-info">Tanpa Resep</span>';
                } else {
                    $html .= '<span class="badge badge-light-warning">Unknown</span>';
                }
                return $html;
            })
            // ->addColumn('action', function ($product) {
            //     return '
            //         <div class="dropdown text-end">
            //             <button class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary dropdown-toggle" 
            //                 type="button" 
            //                 id="dropdownMenuButton' . $product->id . '" 
            //                 data-bs-toggle="dropdown" 
            //                 aria-expanded="false">
            //                 <i class="ki-outline ki-gear fs-5 ms-1"></i>
            //             </button>

            //             <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $product->id . '">
            //                 <li>
            //                     <a class="dropdown-item" href="' . route('products.edit', $product->id) . '">
            //                         Edit
            //                     </a>
            //                 </li>
            //                 <li>
            //                     <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteProduct(' . $product->id . ')">
            //                         Delete
            //                     </a>
            //                 </li>
            //             </ul>
            //         </div>
            //     ';
            // })
            ->addColumn('action', function ($row) {
                $editUrl = route('products.edit', $row->id);
                $deleteUrl = route('products.destroy', $row->id);
                $name = e($row->name);

                return '
                <div class="dropstart">
                    <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi ' . $name . '">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">
                        <li>
                            <a class="dropdown-item text-primary d-flex justify-content-center" href="' . $editUrl . '" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-primary d-flex justify-content-center" href="javascript:void(0)" onclick="deleteProduct(' . $row->id . ')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </li>
                    </ul>
                </div>';
            })
            ->rawColumns(['name', 'action', 'price', 'status'])
            ->make(true);
    }

    public function get_data_stock(Request $request)
    {
        $query = DB::table('product_stock')->orderBy('stock_available', 'desc');
        if ($request->has('stock_filter')) {
            if ($request->stock_filter === 'ada') {
                $query->where('stock_available', '>', 0);
            } elseif ($request->stock_filter === 'kosong') {
                $query->where('stock_available', '=', 0);
            }
        }
        $data = $query->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($product) {
                $html = '
                    <div class="d-flex align-items-center">';
                // if (isset($product->image)) {
                //     $url = asset('storage/' . $product->image);
                //     $html .= '<img src="' . $url . '" alt="Product Image" width="50">';
                // } else {
                //     $html .= '<a href="javascript:void(0)" class="symbol symbol-50px">
                //             <span class="symbol-label" style="background-image:url(assets/media/svg/files/blank-image.svg);"></span>
                //         </a>';
                // }
                $html .= '<div class="ms-5">
                            <a href="' . url('products/') . $product->id . '/show' . '" class="text-gray-800 text-hover-primary fs-5 fw-bold" 
                               data-kt-ecommerce-product-filter="product_name">
                                ' . e($product->name) . '
                            </a>
                        </div>
                    </div>
                ';
                return $html;
            })
            ->addColumn('price', function ($product) {
                return '<span class="badge badge-light-primary editable-price" data-id="' . $product->id . '" data-value="' . toNumber($product->price) . '">Rp.' . $product->price . '</span>';
            })
            ->addColumn('hpp', function ($product) {
                return '<span class="badge badge-light-primary" data-id="' . $product->id . '" data-value="' . $product->hpp . '">Rp' . toNumber($product->hpp) . '</span>';
            })
            ->addColumn('stock_available', function ($product) {
                return '<span class="badge badge-light-' . $product->stock_status . '">' . $product->stock_available . ' ' . $product->unit . '</span>';
            })
            ->addColumn('category', function ($item) {
                return $item->category_id;
            })
            ->addColumn('action', function ($item) {
                return '
                    <a href="' . url('product-stock') . '/' . $item->id . '/show' . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="View">
                        <i class="fa fa-eye"></i>
                    </a>
                ';
            })
            ->rawColumns(['name', 'action', 'price', 'stock_available', 'hpp', 'category'])
            ->make(true);
    }

    public function get_data_available(Request $request)
    {
        return DB::table('product_stock')
            ->where('stock_available', '>', 0)
            ->where('name', 'like', '%' . $request->search . '%')
            ->select('id', 'name', 'stock_available')
            ->limit(20)
            ->get();
    }

    public function get_data_stock_show(Request $request)
    {
        $query = DB::table('transaction_stock')
            ->join('products', 'transaction_stock.product_id', '=', 'products.id')
            ->join('product_units', 'products.product_unit', '=', 'product_units.id')
            ->select('transaction_stock.*', 'products.name', 'product_units.abbreviation as unit')
            ->where('transaction_stock.product_id', $request->product_id)
            ->orderBy('transaction_stock.date', 'asc');
        $data = $query->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($product) {
                $html = '
                    <div class="d-flex align-items-center">';
                $html .= '<div class="ms-5">
                            <a href="' . url('products/') . $product->product_id . '/show' . '" class="text-gray-800 text-hover-primary fs-5 fw-bold" 
                               data-kt-ecommerce-product-filter="product_name">
                                ' . e($product->name) . '
                            </a>
                        </div>
                    </div>
                ';
                return $html;
            })
            ->addColumn('quantity', function ($product) {
                if ($product->quantity > 0) {
                    return '<span class="badge badge-light-success">' . $product->quantity . ' ' . $product->unit . '</span>';
                } else {
                    return '<span class="badge badge-light-danger">' . $product->quantity . ' ' . $product->unit . '</span>';
                }
            })
            ->addColumn('date', function ($item) {
                return dateindo($item->date);
            })
            ->rawColumns(['name', 'quantity'])
            ->make(true);
    }
}
