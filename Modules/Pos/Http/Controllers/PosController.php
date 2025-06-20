<?php

namespace Modules\Pos\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pos\Entities\PosDetailModel;
use Modules\Pos\Entities\PosModel;
use Modules\Pos\Entities\Payment;
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

class PosController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('pos::pos.index2');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['alpinejs'] = true;
        $data['invoice_number'] = PosModel::getOrderNumber();
        return view('pos::pos.create2', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'customer_id' => 'nullable|exists:customer,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.total_input' => 'required|numeric|min:0',
        ]);

        $sum_discount = array_sum(array_column($request->items, 'discount'));
        $sum_total_input = array_sum(array_column($request->items, 'total_input'));

        DB::beginTransaction();
        try {

            $userId = Auth::id(); // Ambil user sekali
            $pos = new PosModel([
                'customer_id' => $request->customer_id,
                'date' => date('Y-m-d'),
                'total' => $sum_total_input - $sum_discount,
                'created_by' => $userId,
            ]);
            $pos->save();
            // $transaction = PosModel::create([
            //     'customer_id' => $request->customer_id,
            //     'total' => collect($request->items)->sum(fn($i) => $i['total_input'] - ($i['discount'] ?? 0))
            // ]);
            // dd($pos->id);

            $posDetail = [];
            foreach ($request->items as $item) {
                $posDetail[] = [
                    'pos_id' => $pos->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'discount' => $item['discount'] ?? 0,
                    'subtotal' => $item['total_input'],
                ];
            }
            PosDetailModel::insert($posDetail);

            DB::commit();

            return response()->json([
                'success' => true,
                'id' => $pos->id,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data['data'] = PosModel::with('customer')->findOrFail($id);
        $data['detail'] = PosDetailModel::with('product')->where('pos_id', $id)->get();
        // dd($data);
        return view('pos::pos.receipt', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('pos::edit');
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
            $pos = PosModel::findOrFail($id);
            $pos->delete();
            PosDetailModel::where('pos_id', $id)->delete();
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

    public function savePayment(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'date' => 'required|date',
            'transaction_id' => 'required|exists:pos_transaction,id',
            'branch_id' => 'required|exists:branch,id',
            // 'account_id' => 'required|exists:account,id',
            'payment_id' => 'required|exists:payment_method,id',
            'total_payment' => 'required|numeric|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Simpan ke tabel pembayaran
            $payment = new Payment([
                'date' => $data['date'],
                'pos_id' => $data['transaction_id'],
                'branch_id' => $data['branch_id'],
                // 'account_id' => $data['account_id'],
                'payment_method' => $data['payment_id'],
                'total' => $data['total_payment'],
                'created_by' => Auth::user()->id_user,
            ]);
            // dd($payment);
            $payment->save();

            $totalPayment = Payment::where('pos_id', $data['transaction_id'])
                ->sum('total');

            $pos = PosModel::findOrFail($data['transaction_id']);
            $total = $pos->total;
            $pos->paid = $totalPayment;
            if ($totalPayment > $total) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total pembayaran tidak boleh lebih dari total transaksi.',
                ], 500);
            }
            $status = 'debt';
            if ($totalPayment == $total) {
                $status = 'paid';
            }
            $pos->status = $status;
            $pos->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'payment' => $payment,
                'pos' => $pos,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function listPayment($id)
    {
        $payments = Payment::with('paymentMethod')->where('pos_id', $id)
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($payments);
    }

    public function showReceipt($id)
    {
        $data['data'] = PosModel::with('customer')->findOrFail($id);
        $data['detail'] = PosDetailModel::with('product')->where('pos_id', $id)->get();
        // dd($data);
        return view('pos::pos.receipt2', $data);
    }

    public function saveTransaction(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            // 'customer_id' => 'nullable|exists:customer,id',
            'customer_id' => 'nullable',
            'date' => 'required|date',
            'invoice_number' => 'nullable',
            'items' => 'required|array',
            'subtotal' => 'required|numeric',
            'discount' => 'required|numeric',
            'total' => 'required|numeric',
            'status' => 'nullable|in:draft,paid,unpaid',
        ]);

        try {
            $userId = Auth::id();
            DB::beginTransaction();
            // Simpan ke tabel transaksi (buat dulu kalau belum ada)
            $pos = new PosModel([
                'customer_id' => $data['customer_id'],
                'date' => $data['date'],
                'invoice_number' => PosModel::getOrderNumber(),
                'subtotal' => $data['subtotal'],
                'total' => $data['total'],
                'discount' => $data['discount'],
                'status' => $data['status'] ?? 'draft',
                'created_by' => $userId,
            ]);
            $pos->save();

            // Simpan item transaksi
            $transaksiId = $pos->id;
            foreach ($data['items'] as $item) {
                PosDetailModel::insert([
                    'pos_id' => $transaksiId,
                    'product_id' => $item['id'],
                    'price' => $item['price'],
                    'quantity' => $item['qty'],
                    'discount' => $item['discount'] ?? 0,
                    'subtotal' => $item['total_input'] ?? ($item['price'] * $item['qty']) - $item['discount'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'transaksi_id' => $transaksiId
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function payment($id)
    {
        $data['alpinejs'] = true;
        $data['data'] = PosModel::with('customer')->findOrFail($id);
        $data['detail'] = PosDetailModel::with('product')->where('pos_id', $id)->get();
        // dd($data);
        return view('pos::pos.payment', $data);
    }

    public function get_data(Request $request)
    {
        $query = PosModel::with('customer', 'payment');
        if ($request->has('status_filter') && $request->status_filter !== 'all') {
            $query = $query->where('status', $request->status_filter);
        }
        $data = $query->get();
        // dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                $colors = ['warning', 'success', 'info', 'primary'];
                $color = $colors[$item->id % count($colors)];
                $html = '<div class="d-flex align-items-center">';
                if (isset($item->customer->name)) {
                    $html .= '<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                <a href="' . url('pos') . '/' . $item->id . '/show' . '">
                                    <div class="symbol-label fs-3 bg-light-' . $color . ' text-' . $color . '">' . strtoupper(substr($item->customer->name, 0, 1)) . '</div>
                                </a>
                            </div>
                            <div class="ms-5">
                                <a href="' . url('pos') . '/' . $item->id . '/show' . '" class="text-gray-800 text-hover-primary fs-5 fw-bold">' . $item->customer->name . '</a>
                                <br>
                                <span class="text-muted d-block fs-7">Rp' . toNumber($item->total) . '</span>';
                    if ($item->status == 'paid') {
                        $html .= '<span class="badge badge-light-success">Paid</span>';
                    } else if ($item->status == 'draft') {
                        $html .= '<span class="badge badge-light-danger">Draft</span>';
                    } else {
                        $html .= '<span class="badge badge-light-warning">' . $item->status . '</span>';
                    }
                    $html .= '</div>';
                } else {
                    $html .= '<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                <a href="' . url('pos') . '/' . $item->id . '/show' . '">
                                    <div class="symbol-label fs-3 bg-light-' . $color . ' text-' . $color . '">' . strtoupper(substr('#', 0, 1)) . '</div>
                                </a>
                            </div>
                            <div class="ms-5">
                                <a href="' . url('pos') . '/' . $item->id . '/show' . '" class="text-gray-800 text-hover-primary fs-5 fw-bold">Tidak Ada Customer</a>
                                <br>
                                <span class="text-muted d-block fs-7">Rp' . toNumber($item->total) . '</span>';
                    if ($item->status == 'paid') {
                        $html .= '<span class="badge badge-light-success">Paid</span>';
                    } else if ($item->status == 'draft') {
                        $html .= '<span class="badge badge-light-danger">Draft</span>';
                    } else {
                        $html .= '<span class="badge badge-light-warning">' . $item->status . '</span>';
                    }
                    $html .= '</div>';
                }
                $html .= '</div>';
                return $html;
            })
            ->addColumn('total_price', function ($item) {
                return 'Rp. ' . number_format($item->total_price, 0, ',', '.');
            })
            ->addColumn('total_quantity', function ($item) {
                return $item->total_quantity;
            })
            ->addColumn('date', function ($item) {
                $html = '' . dateindo($item->date) . '<br>';
                // $html .= '<span class="badge badge-light-primary">'. $item->payment->name .'</span>';
                return $html;
            })
            ->addColumn('action', function ($item) {
                $html = '';
                $html .= '
                    <div class="dropstart">
                        <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">                        
                            <li>
                                <a class="dropdown-item" href="' . route('pos.show', $item->id) . '">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="' . route('pos.payment', $item->id) . '">
                                    <i class="bi bi-cash-stack"></i>
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
            ->rawColumns(['name', 'action', 'date'])
            ->make(true);
    }
}
