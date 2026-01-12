<?php
namespace Modules\Transaction\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Master\Entities\Branch;
use Modules\Master\Entities\Product;
use Modules\Master\Entities\ProductChild;
use Modules\Master\Entities\Supplier;
use Modules\Master\Entities\UserBranch;
use Modules\Transaction\Entities\ProductStock;
use Modules\Transaction\Entities\Wholesale;
use Modules\Transaction\Entities\WholesaleProduct;
use Yajra\DataTables\Facades\DataTables;

class WholesaleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data['branches'] = Branch::whereIn('id', UserBranch::getUserBranch())->get();
        return view('transaction::wholesale.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    // public function create()
    // {
    //     $wholsale = new Wholesale();
    //     $wholsale->order_date = date('Y-m-d');
    //     $wholsale->status = 'draft';
    //     $wholsale->created_by = Auth::user()->id_user;
    //     $wholsale->save();

    //     $data['products'] = Product::whereNull('parent_id')->get(); // optional: reset index numerik
    //     $data['suppliers'] = Supplier::all();
    //     return redirect('wholesale/' . $wholsale->id . '/edit');
    //     // return view('transaction::wholesale.create', $data);
    // }
    public function create()
    {
        $data['alpinejs']       = true;
        $data['data']           = null;
        $data['detail']         = null;
        $data['invoice_number'] = Wholesale::getOrderNumber();
        return view('transaction::wholesale.create2', $data);
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
            'order_date'  => 'required',
            'description' => 'nullable|string|max:255',
            'products'    => 'required|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $wholesale               = new Wholesale();
            $wholesale->order_number = Wholesale::getOrderNumber();
            $wholesale->order_date   = $request->order_date;
            $wholesale->description  = $request->description;
            $wholesale->status       = $request->submit_type;
            $wholesale->created_by   = Auth::user()->id_user;
            $wholesale->save();

            $wholesaleId = $wholesale->id;
            foreach ($request->products as $product) {
                $wholesaleDetail               = new WholesaleProduct();
                $wholesaleDetail->wholesale_id = $wholesaleId;
                $wholesaleDetail->quantity     = $product['qty'];
                $wholesaleDetail->price        = $product['price'];
                $wholesaleDetail->total_price  = $product['price'] * $product['qty'];
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
        // $data['suppliers'] = Supplier::all();
        // $data['data'] = Wholesale::findOrFail($id);
        // return view('transaction::wholesale.show', $data);

        $data['alpinejs']       = true;
        $data['data']           = Wholesale::with('branch')->findOrFail($id);
        $data['detail']         = WholesaleProduct::with('product', 'product.unit')->where('wholesale_id', $id)->get();
        $data['invoice_number'] = $data['data']->order_number;
        return view('transaction::wholesale.create2', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    // public function edit($id)
    // {
    //     $data['suppliers'] = Supplier::all();
    //     $data['data'] = Wholesale::findOrFail($id);
    //     return view('transaction::wholesale.create', $data);
    // }

    public function edit($id)
    {
        $data['alpinejs']       = true;
        $data['data']           = Wholesale::with('branch')->findOrFail($id);
        $data['detail']         = WholesaleProduct::with('product', 'product.unit')->where('wholesale_id', $id)->get();
        $data['invoice_number'] = $data['data']->order_number;
        return view('transaction::wholesale.create2', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function update_product(Request $request, $id)
    {
        // Validasi input
        // dd($request->all());
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:supplier,id',
            'price'       => 'required|numeric',
            'qty'         => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();
            $wholesaleProduct              = WholesaleProduct::findOrFail($id);
            $wholesaleProduct->supplier_id = $validated['supplier_id'];
            $wholesaleProduct->price       = $validated['price'];
            $wholesaleProduct->quantity    = $validated['qty'];
            $wholesaleProduct->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Product updated failed']);
        }

        return response()->json(['message' => 'Product updated successfully']);
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
            'order_date'  => 'required|date',
            'submit_type' => 'required|in:draft,posting',
        ]);

        try {
            DB::beginTransaction();
            $wholesale = Wholesale::find($id);
            if ($wholesale->order_number == null) {
                // Generate order number if not already set
                $wholesale->order_number = Wholesale::getOrderNumber();
            }
            $wholesale->status      = $request->submit_type;
            $wholesale->created_by  = Auth::user()->id_user;
            $wholesale->order_date  = $request->order_date;
            $wholesale->description = $request->description;

            if ($request->submit_type == 'posting') {
                $wholesaleProduct = WholesaleProduct::with('product', 'productStock')->where('wholesale_id', $id)->get();
                foreach ($wholesaleProduct as $key => $value) {
                    $wholesaleProductChild = ProductChild::with('product', 'productStock')->where('parent_id', $value->product_id);
                    $totalStock            = $wholesaleProductChild->get()->sum(function ($child) {
                        return $child->productStock->stock_available ?? 0;
                    });
                    // dd($totalStock);
                    $stock  = $value->productStock->stock_available ?? 0;
                    $stock += $totalStock;
                    $hpp    = $value->product->hpp ?? 0;

                    // dd($stock);
                    if ($stock == 0) {
                        Product::where("id", $value->product_id)->update([
                            'hpp' => $value->price,
                        ]);
                        Product::whereIn('id', $wholesaleProductChild->pluck('product_id')->toArray())->update([
                            'hpp' => $value->price,
                        ]);
                    } else {
                        $newHpp = collect([$hpp, $value->price])->avg();
                        Product::where("id", $value->product_id)->update([
                            'hpp' => $newHpp,
                        ]);
                        Product::whereIn('id', $wholesaleProductChild->pluck('product_id')->toArray())->update([
                            'hpp' => $newHpp,
                        ]);
                    }
                }
            }

            // dd($wholesaleProduct);
            $wholesale->save();

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
                'message' => 'Data berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function receive_productOld($id)
    {
        $data['data']             = Wholesale::findOrFail($id);
        $data['selectedProducts'] = WholesaleProduct::with('product')
            ->where('wholesale_id', $id)
            ->get()
            ->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'name'  => $item->product->name,
                    'sku'   => $item->product->sku,
                    'unit'  => $item->product->unit->abbreviation,
                    'price' => number_format($item->product->price, 0, ',', '.'),
                    'image' => asset('storage/' . $item->product->image),
                    'qty'   => $item->quantity,
                ];
            });
        // dd($data);
        return view('transaction::wholesale.receive_product', $data);
    }

    public function receive_product(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $wholesale         = Wholesale::findOrFail($id);
            $wholesale->status = 'complete';
            $wholesale->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diproses.',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function save_receive(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'wholesale_id' => 'required|exists:wholesale,id',
            'products'     => 'required|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $wholesale         = Wholesale::findOrFail($request->wholesale_id);
            $wholesale->status = 'complete';
            $wholesale->save();

            foreach ($request->products as $key => $product) {
                $wholesaleDetail           = WholesaleProduct::findOrFail($key);
                $wholesaleDetail->quantity = $product['quantity'];
                $wholesaleDetail->hpp      = $product['price'];
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
        // dd($request->all());
        $data = WholesaleProduct::where('wholesale_id', $id)->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                $html = '';
                if ($item->supplier_id != null) {
                    $html .= $item->product->name . '<br>' . $item->supplier->name . '<br> Jumlah : ' . $item->quantity . ' ' . $item->product->unit->abbreviation;;
                } else {
                    $html .= $item->product->name . '<br>' . '<span class="text-danger">Tidak ada supplier</span>' . '<br> Jumlah : ' . $item->quantity . ' ' . $item->product->unit->abbreviation;;
                }
                return $html;
            })
            ->addColumn('supplier', function ($item) {
                if (isset($item->supplier_id) && $item->supplier_id != null) {
                    return $item->supplier->name;
                } else {
                    return '-';
                }
            })
            ->addColumn('price', function ($item) {
                return 'Rp' . toNumber($item->price);
            })
            ->addColumn('total', function ($item) {
                return 'Rp' . toNumber($item->total_price);
            })
            ->addColumn('action', function ($item) use ($request) {
                $html = '';

                if ($request->url == 'wholesale/show') {
                    $html .= '';
                } else {
                    $html .= '
                    <div class="dropstart">
                    <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">
                        <li>
                            <a class="dropdown-item text-primary d-flex justify-content-center" href="javascript:void(0)" onclick="editProduct(' . $item->id . ')" title="Edit">
                                <i class="bi bi-pencil-square"></i>
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
            ->rawColumns(['name', 'action'])
            ->make(true);
    }

    public function save_product(Request $request)
    {
        $validated = $request->validate([
            'wholesale_id' => 'required|exists:wholesale,id',
            'id'           => 'required|exists:products,id',
            'supplier_id'  => 'nullable|exists:supplier,id',
            'qty'          => 'required',
            'price'        => 'required',
            'sell_price'   => 'nullable',
        ]);

        $cek = WholesaleProduct::where('wholesale_id', $validated['wholesale_id'])
            ->where('product_id', $validated['id'])
            ->first();
        if ($cek) {
            return response()->json([
                'message' => 'Product sudah ada',
            ], 404);
        }

        if ($validated['qty'] <= 0) {
            return response()->json([
                'message' => 'Qty tidak boleh 0',
            ], 404);
        }
        try {
            DB::beginTransaction();
            $wholesaleProduct               = new WholesaleProduct();
            $wholesaleProduct->wholesale_id = $validated['wholesale_id'];
            $wholesaleProduct->product_id   = $validated['id'];
            $wholesaleProduct->supplier_id  = $validated['supplier_id'];
            $wholesaleProduct->quantity     = $validated['qty'];
            $wholesaleProduct->price        = $validated['price'];
            $wholesaleProduct->total_price  = $validated['price'] * $validated['qty'];
            $wholesaleProduct->save();

            if ($validated['sell_price'] != null) {
                $product        = Product::findOrFail($validated['id']);
                $product->price = $validated['sell_price'];
                $product->save();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Product gagal disimpan.' . $e->getMessage(),
                'data'    => $wholesaleProduct,
            ], 404);
        }

        // Kirim response JSON
        return response()->json([
            'message' => 'Product berhasil disimpan.',
            'data'    => $wholesaleProduct,
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
            'data' => $data,
        ], 201);
    }

    public function receive_process($id)
    {
        $wholesale        = Wholesale::findOrFail($id);
        $wholsaleProduct  = WholesaleProduct::with('product', 'supplier')->where('wholesale_id', $id)->get();
        $data['data']     = $wholesale;
        $data['products'] = $wholsaleProduct;

        return view('transaction::wholesale.process', $data);
    }

    public function update_receive_product(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $wholesaleProduct         = WholesaleProduct::findOrFail($id);
            $wholesaleProduct->status = 'complete';
            $wholesaleProduct->save();
            DB::commit();
            return response()->json([
                'message' => 'Product berhasil diterima.',
            ], 201);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Product gagal diterima.',
            ], 404);
        }
    }

    public function set_selesai(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $wholesale         = Wholesale::findOrFail($id);
            $wholesale->status = 'complete';
            $wholesale->save();
            DB::commit();
            return response()->json([
                'message' => 'Product berhasil diterima.',
            ], 201);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Product gagal diterima.',
            ], 404);
        }
    }

    public function getProductTableData(Request $request)
    {
        $searchValue = $request->input('searchValue'); // Ambil nilai pencarian
        if (empty($searchValue)) {
            return DataTables::of([])->make(true); // Kembalikan tabel kosong jika tidak ada pencarian
        }
        $query = Product::query()
            ->with('category')
            ->with('get_stock')
            ->where('name', 'like', '%' . $searchValue . '%');
        if ($request->url != 'parcel') {
            $query = $query->whereNull('is_variant');
        }

        // $data = Product::whereNull('parent_id')->get();
        $data = $query->get();
        return DataTables::of($data)
        // ->addColumn('checkbox', function ($row) {
        //     return '<div class="form-check form-check-sm form-check-custom form-check-solid">
        //             <input class="form-check-input" type="checkbox" value="' . $row->id . '" />
        //         </div>';
        // })
            ->addColumn('name', function ($row) {
                return '<a href="javascript:void(0)" class="text-gray-800 text-hover-primary fs-5 fw-bold check-product">'
                . e($row->name) .
                    '</a>';
            })
            ->addColumn('qty_remaining', function ($row) {
                return '<span class="fw-bold ms-3 check-product">' . ($row->get_stock->stock_available) . '</span>';
            })
            ->rawColumns(['checkbox', 'name', 'qty_remaining'])
            ->make(true);
    }

    public function saveTransaction(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'branch_id'      => 'required|exists:branch,id',
            'date'           => 'required|date',
            'invoice_number' => 'nullable',
            'items'          => 'required|array',
            'total'          => 'required|numeric',
            'subtotal'       => 'required|numeric',
            'status'         => 'nullable|in:draft,posting',
        ]);

        try {
            $userId = Auth::id();
            DB::beginTransaction();
            $cek = Wholesale::where('order_number', $data['invoice_number'])->first();
            if ($cek) {
                $pos       = Wholesale::find($cek->id);
                $posDetail = WholesaleProduct::where('wholesale_id', $cek->id);
                WholesaleProduct::where('wholesale_id', $cek->id)->delete();
                $pos->delete();
            }
            // Simpan ke tabel sortir (buat dulu kalau belum ada)
            $pos = new Wholesale([
                'uuid'         => Str::uuid(),
                'branch_id'    => $data['branch_id'],
                'order_date'   => $data['date'],
                'order_number' => $data['invoice_number'],
                'status'       => $data['status'] ?? 'draft',
                'created_by'   => $userId,
            ]);
            // dd($pos);
            $pos->save();

            // Simpan item transaksi
            $transaksiId = $pos->id;
            foreach ($data['items'] as $item) {
                if (is_numeric($item['id'])) {

                    if ($data['status'] == 'posting') {
                        $productStock = (float) (ProductStock::where('id', $item['id'])->value('stock_available') ?? 0);
                        if ($productStock == 0) {
                            Product::where("id", $item['id'])->update([
                                'hpp'           => $item['price'],
                                'total_belanja' => $item['total_input'],
                            ]);
                            $ProductChild = ProductChild::where('parent_id', $item['id']);
                            Product::whereIn('id', $ProductChild->pluck('product_id')->toArray())->update([
                                'hpp'           => $item['price'],
                                'total_belanja' => $item['total_input'],
                            ]);
                        } else {
                            $newStock     = $productStock + $item['qty'];
                            $totalAset    = Product::where("id", $item['id'])->value('hpp') * $productStock;
                            $totalBelanja = $totalAset + $item['total_input'];
                            $newHpp       = $totalBelanja / $newStock;
                            // dd($newStock, $totalAset, $newHpp);
                            Product::where("id", $item['id'])->update([
                                'hpp'           => $newHpp,
                                'total_belanja' => $totalBelanja,
                            ]);
                            $ProductChild = ProductChild::where('parent_id', $item['id']);
                            Product::whereIn('id', $ProductChild->pluck('product_id')->toArray())->update([
                                'hpp'           => $newHpp,
                                'total_belanja' => $totalBelanja,
                            ]);
                        }
                    }

                    WholesaleProduct::insert([
                        'wholesale_id' => $transaksiId,
                        'product_id'   => $item['id'],
                        'price'        => $item['price'],
                        'quantity'     => $item['qty'],
                        // 'discount' => $item['discount'] ?? 0,
                        'total_price'  => $item['total_input'],
                        'created_at'   => now(),
                        'created_by'   => $userId,
                    ]);

                    if ($item['sell'] != null) {
                        $product        = Product::findOrFail($item['id']);
                        $product->price = $item['sell'];
                        $product->save();
                    }
                }
            }

            DB::commit();
            DB::disconnect();

            return response()->json([
                'success'      => true,
                'message'      => 'Transaksi berhasil disimpan',
                'transaksi_id' => $transaksiId,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            DB::disconnect();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function get_data(Request $request)
    {
        // dd($request->all());
        $data = Wholesale::getData($request);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                $colors = ['warning', 'success', 'info', 'primary'];
                $color  = $colors[$item->id % count($colors)];
                return '<div class="d-flex align-items-center">
                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                <a href="' . url('wholesale') . '/' . $item->id . '/show' . '">
                                    <div class="symbol-label fs-3 bg-light-' . $color . ' text-' . $color . '">' . strtoupper(substr($item->order_number, 0, 1)) . '</div>
                                </a>
                            </div>
                            <div class="ms-5">
                                <a href="' . url('wholesale') . '/' . $item->id . '/show' . '" class="text-gray-800 text-hover-primary fs-5 fw-bold">#' . $item->order_number . '</a>
                                <br>
                                <span class="text-muted d-block"> Jml Prod : ' . $item->total_product . '</span>
                                <span class="badge badge-light-danger">' . ucwords(strtolower($item->created_by)) . '</span>
                            </div>
                        </div>';
            })
            ->addColumn('order_date', function ($item) {
                return date('d F Y H:i', strtotime($item->created_at));
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 'draft') {
                    return '<span class="badge badge-light-primary">Draft</span>';
                } elseif ($item->status == 'posting') {
                    return '<span class="badge badge-light-success">Posting</span>';
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
                    <div class="dropstart">
                        <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">
                            <li>
                                <a class="dropdown-item" href="' . route('wholesale.show', $item->id) . '">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="' . route('wholesale.edit', $item->id) . '">
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
                } else {
                    $html .= '
                    <div class="dropstart">
                        <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">
                            <li>
                                <a class="dropdown-item" href="' . route('wholesale.show', $item->id) . '">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    ';
                }
                return $html;
            })
            ->addColumn('wholesale_id', function ($item) {
                return $item->id;
            })
            ->rawColumns(['name', 'action', 'status', 'address'])
            ->make(true);
    }
}
