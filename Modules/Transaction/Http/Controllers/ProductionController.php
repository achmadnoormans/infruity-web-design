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
            'submit_type'        => 'required|in:draft,posting,temp',
            'production_date'    => 'required|date',
            'production_number'  => 'required|string',
            'ingredients'        => 'required|array|min:1',
            'ingredients.*.id'   => 'required|exists:products,id',
            'ingredients.*.quantity' => 'required|numeric|min:0',
            'ingredients.*.hpp'  => 'required|numeric|min:0',
            'quantity'           => 'required|numeric|min:1',
            'staff_id'           => 'nullable|exists:staff,id',
            'notes'              => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Check if there's existing temp production to update
            $production = Production::where('created_by', Auth::user()->id_user)
                ->where('status', 'temp')
                ->where('production_number', $request->production_number)
                ->first();
            
            if (!$production) {
                // Create new production
                $production = new Production();
                $production->production_number = $request->production_number;
                $production->created_by = Auth::user()->id_user;
            }
            
            // Update production data
            $production->product_id = $request->product_id;
            $production->production_date = $request->production_date;
            $production->status = $request->submit_type;
            $production->quantity = $request->quantity;
            $production->staff_id = $request->staff_id;
            $production->description = $request->notes;
            $production->save();

            // Clear existing production details if updating
            ProductionDetail::where('production_id', $production->id)->delete();

            // Add new production details from ingredients array
            foreach ($request->ingredients as $ingredient) {
                $productionDetail = new ProductionDetail();
                $productionDetail->production_id = $production->id;
                $productionDetail->product_id = $ingredient['id'];
                $productionDetail->quantity = $ingredient['quantity'];
                $productionDetail->save();
            }

            DB::commit();
            
            // Handle different submit types like PosController
            if ($request->submit_type == 'temp') {
                return redirect()->back()->with('success', 'Data berhasil disimpan sebagai draft sementara');
            } elseif ($request->submit_type == 'draft') {
                return redirect()->route('production');
            } else {
                // posting - calculate and update HPP
                $totalHpp = 0;
                foreach ($request->ingredients as $ingredient) {
                    $totalHpp += $ingredient['hpp'] * $ingredient['quantity'];
                }
                
                Product::where('id', $request->product_id)->update([
                    'hpp' => $totalHpp / $request->quantity, // HPP per unit
                ]);
                
                return redirect('production')->with('success', 'Produksi berhasil diselesaikan');
            }

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Produksi gagal: ' . $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        try {
            $data['data'] = Production::with(['products', 'products.unit', 'staff', 'creator'])->findOrFail($id);
            $data['production_detail'] = ProductionDetail::with(['products', 'products.unit', 'products.category'])->where('production_id', $id)->get();
            
            // Calculate totals with null safety
            $data['total_hpp'] = $data['production_detail']->sum(function($item) {
                return ($item->products->hpp ?? 0) * $item->quantity;
            });
            
            $data['hpp_per_unit'] = $data['data']->quantity > 0 ? $data['total_hpp'] / $data['data']->quantity : 0;
            
            return view('transaction::production.show', $data);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('production.index')->with('error', 'Data produksi tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('production.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['alpinejs'] = true;
        $data['data']              = Production::find($id);
        $data['production_detail'] = ProductionDetail::with('products')->where('production_id', $id)->get();
        // $data['selectedProduct']   = Product::find($data['data']->product_id);
        $data['receipt'] = Receipt::with('products')->where('product_id', $data['data']->product_id)->first();
        $data['production_number'] = $data['data']->production_number; // Add the production number
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
        try {
            // Simple test first - get all production records
            $productions = Production::with('products')->get();
            
            return DataTables::of($productions)
                ->addColumn('name', function ($item) {
                    $productName = $item->products ? $item->products->name : 'N/A';
                    $quantity = number_format($item->quantity ?? 0, 0, ',', '.');
                    
                    return '<div class="d-flex align-items-center">
                                <div class="ms-5">
                                    <a href="' . route('production.edit', $item->id) . '" class="text-gray-800 text-hover-primary fs-5 fw-bold">#' . ($item->production_number ?? 'N/A') . '</a>
                                    <br>
                                    <span class="text-muted d-block">' . $productName . '</span>
                                    <span class="text-muted d-block">Qty: ' . $quantity . '</span>
                                </div>
                            </div>';
                })
                ->addColumn('production_date', function ($item) {
                    return $item->production_date ? date('d/m/Y', strtotime($item->production_date)) : 'N/A';
                })
                ->addColumn('status', function ($item) {
                    switch ($item->status) {
                        case 'posting':
                            return '<span class="badge badge-light-success">Completed</span>';
                        case 'complete':
                            return '<span class="badge badge-light-success">Complete</span>';
                        case 'draft':
                            return '<span class="badge badge-light-warning">Draft</span>';
                        case 'temp':
                            return '<span class="badge badge-light-info">Temp</span>';
                        default:
                            return '<span class="badge badge-light-secondary">' . ucfirst($item->status ?? 'Unknown') . '</span>';
                    }
                })
                ->addColumn('action', function ($item) {
                    return '
                        <div class="btn-group" role="group">
                            <a href="' . route('production.detail', $item->id) . '" class="btn btn-sm btn-light-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="' . route('production.edit', $item->id) . '" class="btn btn-sm btn-light-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>';
                })
                ->rawColumns(['name', 'action', 'status'])
                ->make(true);
                
        } catch (\Exception $e) {
            \Log::error('Production DataTable Error: ' . $e->getMessage());
            return response()->json([
                'draw' => intval($request->input('draw', 1)),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }
}




