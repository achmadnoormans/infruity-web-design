<?php

namespace Modules\Transaction\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Product;
use Modules\Master\Entities\ProductChild;
use Modules\Transaction\Entities\Sortir;
use Modules\Transaction\Entities\SortirDetail;
use Modules\Transaction\Entities\WholesaleProduct;
use Modules\Transaction\Entities\ProductStock;
use Modules\Transaction\Entities\StockIn;
use Modules\Transaction\Entities\StockOut;
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
use Illuminate\Support\Str;

class SortirController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('transaction::sortir.index2');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['alpinejs'] = true;
        $data['data'] = null;
        $data['detail'] = null;
        $data['invoice_number'] = Sortir::getOrderNumber();
        return view('transaction::sortir.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        // dd($id);
        $data['product'] = DB::table('sortir_view')->where('id', $id)->first();
        // $data['productChild'] = ProductChild::with('product')->where('parent_id', $data['product']->id)->get();
        // dd($data);
        return view('transaction::sortir.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['alpinejs'] = true;
        $data['data'] = Sortir::findOrFail($id);
        $data['detail'] = SortirDetail::with('product', 'product.unit')->where('sortir_id', $id)->get();
        $data['invoice_number'] = $data['data']->invoice_number;
        return view('transaction::sortir.create', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
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
            $pos = Sortir::findOrFail($id);
            $pos->delete();
            SortirDetail::where('sortir_id', $id)->delete();
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

    public function saveTransaction(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'date' => 'required|date',
            'invoice_number' => 'nullable',
            'items' => 'required|array',
            'total' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'status' => 'nullable|in:draft,paid,debt,temp,pending',
        ]);

        try {
            $userId = Auth::id();
            DB::beginTransaction();
            $cek = Sortir::where('invoice_number', $data['invoice_number'])->first();
            if ($cek) {
                $pos = Sortir::find($cek->id);
                $posDetail = SortirDetail::where('sortir_id', $cek->id);
                SortirDetail::where('sortir_id', $cek->id)->delete();
                $pos->delete();
            }
            // Simpan ke tabel transaksi (buat dulu kalau belum ada)
            $pos = new Sortir([
                'uuid' => Str::uuid(),
                'date' => $data['date'],
                'invoice_number' => Sortir::getOrderNumber(),
                'total' => $data['total'],
                'status' => $data['status'] ?? 'draft',
                'created_by' => $userId,
            ]);
            $pos->save();

            // Simpan item transaksi
            $transaksiId = $pos->id;
            foreach ($data['items'] as $item) {
                if (is_numeric($item['id'])) {
                    SortirDetail::insert([
                        'sortir_id' => $transaksiId,
                        'product_id' => $item['id'],
                        'price' => $item['price'],
                        'quantity' => $item['qty'],
                        'discount' => $item['discount'] ?? 0,
                        'subtotal' => $item['total_input'],
                        'created_at' => now(),
                        'created_by' => $userId,
                    ]);
                }
            }

            DB::commit();
            DB::disconnect();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'transaksi_id' => $transaksiId
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            DB::disconnect();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function save_stock(Request $request)
    {
        // dd($request->all());
        Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|array',
        ])->validate();

        try {
            DB::beginTransaction();
            $product = DB::table('product_stock')->where('id', $request->product_id)->first();
            $quantity = $product->stock_available;
            $hpp = $product->hpp ?? 0;

            if (isset($request->quantity)) {
                foreach ($request->quantity as $key => $value) {
                    $stockIn = new StockIn();
                    $stockIn->code = 'sortir';
                    $stockIn->reference_id = $request->product_id;
                    $stockIn->date = date('Y-m-d');
                    $stockIn->product_id = $key;
                    $stockIn->quantity = $value ?? 0;
                    $stockIn->avg_price = $hpp;
                    $stockIn->created_by = Auth::user()->id_user;

                    Product::where("id", $key)->update([
                        'hpp' => $hpp,
                    ]);

                    // $variant = ProductStock::where('id', $key)->first();
                    // $variantStock = (float) $variant->stock_available ?? 0;
                    // $variantHpp = $variant->hpp ?? 0;
                    // if ($variantStock <= 0) {
                    //     Product::where("id", $key)->update([
                    //         'hpp' => $hpp,
                    //     ]);
                    // } else {
                    //     $newHpp = collect([$hpp, $variantHpp])->avg();
                    //     Product::where("id", $key)->update([
                    //         'hpp' => $newHpp,
                    //     ]);
                    // }

                    $stockIn->save();
                }

                $stockOut = new StockOut();
                $stockOut->code = 'sortir';
                $stockOut->reference_id = $request->product_id;
                $stockOut->date = date('Y-m-d');
                $stockOut->product_id = $request->product_id;
                $stockOut->quantity = array_sum($request->quantity) ?? 0;
                $stockOut->avg_price = $hpp;
                $stockOut->created_by = Auth::user()->id_user;
                // dd($stockOut);
                $stockOut->save();
            }

            if (isset($request->buang) && $request->buang > 0) {
                $buang = new StockOut();
                $buang->code = 'buang';
                $buang->reference_id = $request->product_id;
                $buang->date = date('Y-m-d');
                $buang->product_id = $request->product_id;
                $buang->quantity = $request->buang ?? 0;
                $buang->avg_price = $hpp;
                $buang->created_by = Auth::user()->id_user;
                $buang->save();
            }

            if (isset($request->product_transfer_id) && $request->value_transfer > 0) {
                // dd('masuk', $hpp);
                $transfer = new StockOut();
                $transfer->code = 'transfer';
                $transfer->reference_id = $request->product_id;
                $transfer->date = date('Y-m-d');
                $transfer->product_id = $request->product_id;
                $transfer->quantity = $request->value_transfer ?? 0;
                $transfer->avg_price = $hpp;
                $transfer->created_by = Auth::user()->id_user;
                $transfer->save();

                $stockIn = new StockIn();
                $stockIn->code = 'transfer';
                $stockIn->reference_id = $request->product_id;
                $stockIn->date = date('Y-m-d');
                $stockIn->product_id = $request->product_transfer_id;
                $stockIn->quantity = $request->value_transfer ?? 0;
                $stockIn->avg_price = $hpp;
                $stockIn->created_by = Auth::user()->id_user;
                $stockIn->save();
            }
            // dd($stockIn, $stockOut);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())
                ->with('error', 'Sortir gagal' . $e->getMessage());
        }

        return redirect('sortir')->with('success', 'Sortir berhasil');
    }

    public function get_data_old(Request $request)
    {
        $data = DB::table('sortir_view')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                $html = '
                    <div class="d-flex align-items-center">';
                $html .= '<div class="ms-5">
                            <a href="' . url('/products', $item->product_id) . '" class="text-gray-800 text-hover-primary fs-5 fw-bold" 
                               data-kt-ecommerce-product-filter="product_name">
                                ' . e($item->name) . '
                            </a>
                        </div>
                    </div>
                ';
                return $html;
            })
            ->addColumn('quantity', function ($item) {
                $class = 'badge badge-light-primary';
                if ($item->stock_available <= 0) {
                    $class = 'badge badge-light-danger';
                }
                return '<span class="' . $class . '">' . toNumber($item->stock_available) . ' ' . $item->satuan . '</span>';
            })
            ->addColumn('action', function ($item) {
                return '
                    <a href="' . route('sortir.show', $item->id) . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="View">
                        <i class="fa fa-pencil"></i>
                    </a>
                ';
            })
            ->rawColumns(['name', 'action', 'quantity'])
            ->make(true);
    }

    public function get_data(Request $request)
    {
        $query = Sortir::with('createdBy');
        if ($request->has('status_filter') && $request->status_filter !== 'all') {
            $query = $query->where('status', $request->status_filter);
        }
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        $data = $query->orderBy('id', 'DESC');
        // dd($data);
        return DataTables::of($data)
            ->filter(function ($query) use ($request) {
                $search = trim($request->input('search.value'));

                if (!empty($search)) {
                    $query->where(function ($q) use ($search) {
                        $q->where('invoice_number', 'LIKE', "%{$search}%")
                            ->orWhereHas('createdBy', function ($sub) use ($search) {
                                $sub->where('nm_user', 'LIKE', "%{$search}%");
                            });
                    });
                }
            }, true)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                $html = '<div class="d-flex align-items-center">';
                $html .= '<div class="ms-5">';
                $html .= '<a href="' . route('sortir.edit', $item->id) . '" class="text-gray-800 text-hover-primary fs-5 fw-bold">' . $item->invoice_number . '</a>';
                $html .= '<br><span class="text-muted d-block fs-7">Hpp: Rp' . tonumberround($item->total) . '</span>';
                $html .= '<span class="badge badge-light-danger">' . ucwords(strtolower($item->createdBy->nm_user)) . '</span>';
                return $html;
            })
            ->addColumn('date', function ($item) {
                $html = '<span class="text-muted d-block fs-8">' . date('d M Y H:i', strtotime($item->created_at)) . '</span>';
                if ($item->status == 'paid') {
                    $html .= '<span class="badge badge-light-success">Final</span>';
                } else if ($item->status == 'draft') {
                    $html .= '<span class="badge badge-light-danger">Draft</span>';
                } else {
                    $html .= '<span class="badge badge-light-warning">' . $item->status . '</span>';
                }
                return $html;
            })
            ->addColumn('action', function ($item) {
                $html = '';
                $html .= '
                    <div class="dropstart">
                        <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">';
                $html .= '
                            <li>
                                <a class="dropdown-item" href="' . route('sortir.edit', $item->id) . '">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </li>';
                if (!in_array($item->status, ['paid', 'debt'])) {
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
            ->rawColumns(['name', 'action', 'date'])
            ->make(true);
    }
}
