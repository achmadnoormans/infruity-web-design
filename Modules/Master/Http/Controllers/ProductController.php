<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Product;
use Modules\Master\Entities\ProductUnit;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;

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
            'product_name' => 'required',
            'price' => 'required',
            'product_unit_id' => 'required|exists:product_units,id',
            'status' => 'required',
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
            $product->description = strip_tags($request->description ?? '');
            $product->price = $request->price ?? '';
            $product->product_unit = $request->product_unit_id ?? '';
            $product->stock = $request->stock ?? 0;
            $product->limit = $request->limit ?? 0;
            $product->handling = $request->handling ?? '';
            $product->sku = $request->sku ?? '';
            $product->barcode = $request->barcode ?? '';
            $product->status = $request->status ?? '';
            $product->created_by = Auth::user()->id_user;

            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('products', 'public');
                $product->image = $path;
                $product->save();
            }

            $product->save();

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
        $data['data'] = Product::findOrFail($id);
        $data['product_units'] = ProductUnit::all();
        return view('master::products.edit', $data);
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
            'product_name' => 'required',
            'price' => 'required',
            'product_unit_id' => 'required|exists:product_units,id',
            'status' => 'required',
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
            $product->description = strip_tags($request->description ?? '');
            $product->price = $request->price ?? '';
            $product->product_unit = $request->product_unit_id ?? '';
            $product->stock = $request->stock ?? 0;
            $product->limit = $request->limit ?? 0;
            $product->handling = $request->handling ?? '';
            $product->sku = $request->sku ?? '';
            $product->barcode = $request->barcode ?? '';
            $product->status = $request->status ?? '';
            $product->created_by = Auth::user()->id_user;

            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('products', 'public');
                $product->image = $path;
                $product->save();
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

    /**
     * Get data for DataTables
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function get_data(Request $request)
    {
        $data = Product::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($product) {
                $html = '
                    <div class="d-flex align-items-center">';
                if (isset($product->image)) {
                    $url = asset('storage/' . $product->image);
                    $html .= '<img src="'. $url .'" alt="Product Image" width="50">';
                } else {
                    $html .= '<a href="javascript:void(0)" class="symbol symbol-50px">
                            <span class="symbol-label" style="background-image:url(assets/media/svg/files/blank-image.svg);"></span>
                        </a>';
                }
                $html .= '<div class="ms-5">
                            <a href="apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary fs-5 fw-bold" 
                               data-kt-ecommerce-product-filter="product_name">
                                ' . e($product->name) . '
                            </a>
                        </div>
                    </div>
                ';
                return $html;
            })
            ->addColumn('price', function($product) {
                return '<span class="badge badge-light-success editable-price" data-id="' . $product->id . '" data-value="' . $product->price . '">Rp.' . $product->price . '</span>';
            })
            ->addColumn('action', function ($product) {
                return '
                    <div class="dropdown text-end">
                        <button class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary dropdown-toggle" 
                            type="button" 
                            id="dropdownMenuButton' . $product->id . '" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                            Actions
                            <i class="ki-outline ki-down fs-5 ms-1"></i>
                        </button>
            
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $product->id . '">
                            <li>
                                <a class="dropdown-item" href="' . route('products.edit', $product->id) . '">
                                    Edit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteProduct(' . $product->id . ')">
                                    Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                ';
            })
            ->rawColumns(['name', 'action', 'price'])
            ->make(true);
    }
}
