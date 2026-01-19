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
use Modules\Master\Entities\Product;
use Modules\Transaction\Entities\Production;
use Modules\Transaction\Entities\ProductionDetail;
use Modules\Transaction\Entities\ProductReceipt;
use Modules\Transaction\Entities\Receipt;
use Yajra\DataTables\Facades\DataTables;

class ProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('transaction::production.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['alpinejs'] = true;

        // Cek apakah ada temp production untuk user ini
        $draft = Production::with(['products'])
            ->where('created_by', Auth::user()->id_user)
            ->where('status', 'temp')
            ->orderBy('id', 'desc')
            ->first();

        if ($draft) {
            // Jika ada draft, load data draft
            $data['data'] = $draft;
            $data['production_detail'] = ProductionDetail::with('products')->where('production_id', $draft->id)->get();
            $data['production_number'] = $draft->production_number;
        } else {
            // Jika tidak ada draft, siapkan data kosong
            $data['data'] = null;
            $data['production_detail'] = null;
            $data['production_number'] = Production::getOrderNumber();
        }

        return view('transaction::production.create', $data);
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
            'product_id'         => 'required|exists:products,id',
            'submit_type'        => 'required|in:draft,posting',
            'production_date'    => 'required|date',
            'product_receipt_id' => 'required|array',
            'quantity'           => 'required',
            'staff_id'           => 'nullable|exists:staff,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $production                    = new Production();
            $production->production_number = Production::getOrderNumber();
            $production->product_id        = $request->product_id;
            $production->production_date   = $request->production_date;
            $production->status            = $request->submit_type;
            $production->created_by        = Auth::user()->id_user;
            $production->quantity          = $request->quantity;
            $production->staff_id          = $request->staff_id;
            $production->save();

            $productionId = $production->id;
            foreach ($request->product_receipt_id as $key => $product) {
                $productionDetail                = new ProductionDetail();
                $productionDetail->production_id = $productionId;
                $productionDetail->product_id    = $product;
                $productionDetail->quantity      = $request->ingredients_quantity[$key];
                // dd($productionDetail);
                $productionDetail->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Produksi gagal' . $e->getMessage());
        }

        return redirect('production')->with('success', 'Produksi berhasil');
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
        $data['data']              = Production::find($id);
        $data['production_detail'] = ProductionDetail::with('products')->where('production_id', $id)->get();
        // $data['selectedProduct']   = Product::find($data['data']->product_id);
        $data['receipt'] = Receipt::with('products')->where('product_id', $data['data']->product_id)->first();
        // dd($data);
        return view('transaction::production.create', $data);
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
        $validator = Validator::make($request->all(), [
            'product_id'      => 'required|exists:receipt,id',
            'submit_type'     => 'required|in:draft,posting,temp',
            'production_date' => 'required|date',
            // 'product_receipt_id' => 'required|array',
            'quantity'        => 'required',
            'staff_id'        => 'nullable|exists:staff,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $receipt   = Receipt::find($request->product_id);
            $productId = $receipt->product_id;
            DB::beginTransaction();
            $production                  = Production::findOrFail($id);
            $production->product_id      = $productId;
            $production->production_date = $request->production_date;
            $production->status          = $request->submit_type;
            $production->created_by      = Auth::user()->id_user;
            $production->quantity        = $request->quantity;
            $production->staff_id        = $request->staff_id;
            $production->save();

            // Jika status temp, redirect ke halaman yang sama untuk continue editing
            if ($request->submit_type == 'temp') {
                DB::commit();
                return redirect()->back()->with('success', 'Data berhasil disimpan sebagai draft');
            }

            // Jika status draft, redirect ke payment
            if ($request->submit_type == 'draft') {
                DB::commit();
                return redirect()->route('production.payment', $id);
            }

            if ($request->submit_type == 'posting') {
                $productionDetail = ProductionDetail::with('products')->where('production_id', $id)->get();
                $hpp              = 0;
                foreach ($productionDetail as $key => $value) {
                    $hpp += $value->products->hpp * $value->quantity;
                }
                Product::where('id', $productId)->update([
                    'hpp' => $hpp,
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Produksi gagal' . $e->getMessage());
        }

        return redirect('production')->with('success', 'Produksi berhasil');
    }

    /**
     * Show payment page (similar to POS)
     * @param int $id
     * @return Renderable
     */
    public function payment($id)
    {
        $data['data'] = Production::with('products')->findOrFail($id);
        $data['production_detail'] = ProductionDetail::with('products')->where('production_id', $id)->get();
        return view('transaction::production.payment', $data);
    }

    /**
     * Save production completion (similar to POS savePayment)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveCompletion(Request $request)
    {
        $data = $request->validate([
            'production_id' => 'required|exists:production,id',
            'completion_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $production = Production::findOrFail($data['production_id']);
            $production->status = 'posting';
            $production->completion_date = $data['completion_date'];
            $production->notes = $data['notes'] ?? null;
            $production->save();

            // Update HPP seperti di method update
            $productionDetail = ProductionDetail::with('products')->where('production_id', $data['production_id'])->get();
            $hpp = 0;
            foreach ($productionDetail as $value) {
                $hpp += $value->products->hpp * $value->quantity;
            }
            Product::where('id', $production->product_id)->update([
                'hpp' => $hpp,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produksi berhasil diselesaikan',
                'production_id' => $data['production_id'],
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyelesaikan produksi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show completion notification (similar to POS paymentNotification)
     * @param int $id
     * @return Renderable
     */
    public function completionNotification($id)
    {
        $data['data'] = Production::with('products')->findOrFail($id);
        $data['production_detail'] = ProductionDetail::with('products')->where('production_id', $id)->get();
        return view('transaction::production.completion-success', $data);
    }

    /**
     * Print production report (similar to POS printNota)
     * @param int $id
     * @return Renderable
     */
    public function printProduction($id)
    {
        $data['data'] = Production::with('products')->findOrFail($id);
        $data['production_detail'] = ProductionDetail::with('products')->where('production_id', $id)->get();
        return view('transaction::production.print', $data);
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
            $production = Production::findOrFail($id);
            $production->delete();
            ProductionDetail::where('production_id', $id)->delete();
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

    public function delete_detail(Request $request, $id)
    {
        try {
            $userId = Auth::user()->id_user;
            DB::beginTransaction();
            $receipt   = Receipt::with('products')->find($request->receipt_id);
            $productId = $receipt->product_id;
            Production::where('id', $id)->update([
                'product_id' => $productId,
                'status'     => 'draft',
            ]);
            ProductionDetail::where('production_id', $id)->delete();
            $newProduct       = ProductReceipt::where('receipt_id', $request->receipt_id)->get();
            $productionDetail = [];
            foreach ($newProduct as $key => $product) {
                $productionDetail[]  = [
                    'production_id' => $id,
                    'product_id'    => $product->product_receipt_id,
                    'quantity'      => $product->quantity,
                    'created_by'    => $userId,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }
            if (! empty($productionDetail)) {
                ProductionDetail::insert($productionDetail);
            }
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

    public function update_product_id(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $receipt   = Receipt::with('products')->find($request->receipt_id);
            $productId = $receipt->product_id;
            Production::where('id', $id)->update([
                'product_id' => $productId,
                'status'     => 'draft',
            ]);
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

    public function get_detail_product(Request $request, $id)
    {
        // dd($request->all());
        $data = ProductionDetail::with('products')->where('production_id', $id)->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                $html  = '';
                $html .= $item->products->name . '<br> Jumlah : ' . $item->quantity . ' ' . $item->products->unit->abbreviation . '<br> Harga : ' . toNumber($item->products->price) . '';
                return $html;
            })
            ->addColumn('hpp', function ($item) {
                return 'Rp' . toNumber($item->products->hpp * $item->quantity);
            })
            ->addColumn('harga_jual', function ($item) {
                return toNumber($item->products->price * $item->quantity);
            })
            ->addColumn('action', function ($item) use ($request) {
                $html = '';

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
                return $html;
            })
            ->rawColumns(['name', 'action', 'harga_jual'])
            ->make(true);
    }

    public function edit_additional_ingredient(Request $request, $id)
    {
        $data = ProductionDetail::with('products')->findOrFail($id);
        return response()->json([
            'data' => $data,
        ], 201);
    }

    public function save_additional_ingredient(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $productionDetail                = new ProductionDetail();
            $productionDetail->production_id = $request->production_id;
            $productionDetail->product_id    = $request->product_id;
            $productionDetail->quantity      = $request->qty;
            $productionDetail->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Product gagal disimpan.' . $e->getMessage(),
                'data'    => $productionDetail,
            ], 404);
        }

        // Kirim response JSON
        return response()->json([
            'message' => 'Product berhasil disimpan.',
            'data'    => $productionDetail,
        ], 201);
    }

    public function update_additional_ingredient(Request $request, $id)
    {
        // dd($request->all());
        $validated = $request->validate([
            'qty'        => 'required|numeric',
            'sell_price' => 'nullable',
        ]);

        try {
            DB::beginTransaction();
            $detailProduct           = ProductionDetail::findOrFail($id);
            $detailProduct->quantity = $validated['qty'];
            $detailProduct->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Product updated failed']);
        }

        return response()->json(['message' => 'Product updated successfully']);
    }

    public function delete_additional_ingredient(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $deteilProduct = ProductionDetail::findOrFail($id);
            $deteilProduct->delete();
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

    public function get_data(Request $request)
    {
        $data = Production::with('products')->whereNotNull('product_id')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                return '<div class="d-flex align-items-center">
                            <div class="ms-5">
                                <a href="' . url('production') . '/' . $item->id . '/show' . '" class="text-gray-800 text-hover-primary fs-5 fw-bold">#' . $item->production_number . '</a>
                                <br>
                                <span class="text-muted d-block">' . $item->products->name . '</span>
                                <span class="text-muted d-block"> Qty : ' . toNumber($item->quantity) . '</span>
                            </div>
                        </div>';
            })
            ->addColumn('production_date', function ($item) {
                return dateindo($item->production_date);
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 'posting') {
                    return '<span class="badge badge-light-success">Completed</span>';
                } elseif ($item->status == 'complete') {
                    return '<span class="badge badge-light-success">Complete</span>';
                } elseif ($item->status == 'draft') {
                    return '<span class="badge badge-light-warning">Draft</span>';
                } elseif ($item->status == 'temp') {
                    return '<span class="badge badge-light-info">Temp</span>';
                } else {
                    return '<span class="badge badge-light-danger">Unknown</span>';
                }
            })
            ->addColumn('status_raw', function ($item) {
                return $item->status;
            })
            ->addColumn('action', function ($item) {
                $html  = '';
                $html .= '
                    <div class="dropstart">
                        <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">
                            <li>
                                <a class="dropdown-item" href="' . route('production.edit', $item->id) . '">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </li>';
                
                if (in_array($item->status, ['temp', 'draft'])) {
                    $html .= '
                            <li>
                                <a class="dropdown-item" href="' . route('production.edit', $item->id) . '">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </li>';
                }
                
                if ($item->status == 'draft') {
                    $html .= '
                            <li>
                                <a class="dropdown-item" href="' . route('production.payment', $item->id) . '">
                                    <i class="bi bi-check-circle"></i>
                                </a>
                            </li>';
                }
                
                $html .= '
                            <li>
                                <a class="dropdown-item" href="' . route('production.print', $item->id) . '">
                                    <i class="fa fa-print"></i>
                                </a>
                            </li>';
                
                if (!in_array($item->status, ['posting']) || Session('role')['id_role'] == 1) {
                    $html .= '
                            <li>
                                <a class="dropdown-item text-primary d-flex justify-content-center" href="javascript:void(0)" onclick="deleteProduct(' . $item->id . ')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </li>';
                }

                $html .= '
                        </ul>
                    </div>
                    ';
                return $html;
            })
            ->addColumn('production_id', function ($item) {
                return $item->id;
            })
            ->rawColumns(['name', 'action', 'status', 'address'])
            ->make(true);
    }
}


