<?php

namespace Modules\Pos\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pos\Entities\PosDetailModel;
use Modules\Pos\Entities\PosModel;
use Modules\Pos\Entities\Payment;
use Modules\Crm\Entities\CustomerTier;
use Modules\Crm\Entities\SettingExp;
use Modules\Pos\Entities\SettingNota;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
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
        $data['alpinejs'] = true;
        return view('pos::pos.index2', $data);
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
                'uuid' => Str::uuid(),
                'date' => $data['date'],
                'nota_number' => date('YmdHis'),
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
                $lastPayment = Payment::findOrFail($payment->id);
                $lastPayment->return = ($totalPayment - $total);
                $lastPayment->save();
            } else {
                $lastPayment = Payment::findOrFail($payment->id);
                $lastPayment->remaining = ($total - $totalPayment);
                $lastPayment->save();
            }
            $status = 'debt';
            if ($totalPayment >= $total) {
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
            'ongkir' => 'required|numeric',
            'total' => 'required|numeric',
            'status' => 'nullable|in:draft,paid,debt',
            'ongkir_date' => 'nullable|date',
            'ongkir_time' => 'nullable|date_format:H:i',
        ]);

        try {
            $userId = Auth::id();
            DB::beginTransaction();
            // Simpan ke tabel transaksi (buat dulu kalau belum ada)
            $pos = new PosModel([
                'uuid' => Str::uuid(),
                'customer_id' => $data['customer_id'],
                'date' => $data['date'],
                'invoice_number' => PosModel::getOrderNumber(),
                'subtotal' => $data['subtotal'],
                'total' => $data['total'],
                'discount' => $data['discount'],
                'ongkir' => $data['ongkir'],
                'ongkir_date' => $data['ongkir_date'] ?? null,
                'ongkir_time' => $data['ongkir_time'] ?? null,
                'status' => $data['status'] ?? 'draft',
                'created_by' => $userId,
            ]);
            $pos->save();

            // Simpan item transaksi
            $transaksiId = $pos->id;
            $settingExp = SettingExp::first();
            foreach ($data['items'] as $item) {
                PosDetailModel::insert([
                    'pos_id' => $transaksiId,
                    'product_id' => $item['id'],
                    'price' => $item['price'],
                    'quantity' => $item['qty'],
                    'discount' => $item['discount'] ?? 0,
                    'subtotal' => $item['total_input'],
                    'exp' => $item['price'] - $item['hpp'],
                    'exp_value' => ($item['price'] - $item['hpp']) * $settingExp->value_exp,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => $userId,
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

    public function printPayment($id)
    {
        // $data['data'] = PosModel::with('customer', 'user')->findOrFail($id);
        $data['listPayment'] = Payment::with('paymentMethod', 'pos')->where('pos_id', $id)->get();
        $data['setting'] = SettingNota::first();
        $data['detail'] = PosDetailModel::with('product')->where('pos_id', $id)->get();
        return view('pos::pos.print-list-payment', $data);
    }

    public function printDraftPayment($id)
    {
        $data['data'] = PosModel::with('customer', 'user')->findOrFail($id);
        $data['listPayment'] = Payment::with('paymentMethod', 'pos')->where('pos_id', $id)->get();
        $data['setting'] = SettingNota::first();
        $data['detail'] = PosDetailModel::with('product')->where('pos_id', $id)->get();
        $data['tier'] = CustomerTier::where('customer_id', $data['data']->customer_id)->first();
        // dd($data);
        return view('pos::pos.print2', $data);
    }

    public function uploadReceipt(Request $request)
    {
        $image = $request->input('image');
        if (!$image)
            return response()->json(['error' => 'No image'], 400);

        // Pisahkan data:image/png;base64,
        $image_parts = explode(";base64,", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = 'receipt_' . time() . '.png';

        // Simpan di public/storage/receipts
        $filePath = public_path('storage/receipts/' . $fileName);
        file_put_contents($filePath, $image_base64);

        return response()->json([
            'url' => asset('storage/receipts/' . $fileName),
        ]);
    }

    public function paymentNotification($id)
    {
        $data['data'] = Payment::with('paymentMethod', 'pos')->findOrFail($id);
        $data['totalPayment'] = Payment::where('pos_id', $data['data']->pos_id)->sum('total');
        return view('pos::pos.payment-success', $data);
    }

    public function printNota($id)
    {
        $data['payment'] = Payment::findOrFail($id);
        $data['data'] = PosModel::with('customer', 'user')->findOrFail($data['payment']->pos_id);
        $data['setting'] = SettingNota::first();
        $data['detail'] = PosDetailModel::with('product')->where('pos_id', $data['payment']->pos_id)->get();
        $data['tier'] = CustomerTier::where('customer_id', $data['data']->customer_id)->first();
        // dd($data);
        return view('pos::pos.print2', $data);
    }

    public function cekNota($id)
    {
        $data['payment'] = Payment::where('uuid', $id)->first();
        if (isset($data['payment'])) {
            $data['data'] = PosModel::with('customer', 'user')->findOrFail($data['payment']->pos_id);
            $data['setting'] = SettingNota::first();
            $data['detail'] = PosDetailModel::with('product')->where('pos_id', $data['payment']->pos_id)->get();
            $data['tier'] = CustomerTier::where('customer_id', $data['data']->customer_id)->first();
            // dd($data);
            return view('pos::pos.print2', $data);
        } else {
            abort(404);
        }
    }


    public function get_data(Request $request)
    {
        $query = PosModel::with('customer', 'payment');
        if ($request->has('status_filter') && $request->status_filter !== 'all') {
            $query = $query->where('status', $request->status_filter);
        }
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        $data = $query->orderBy('id', 'DESC')->get();
        // dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                $html = '<div class="d-flex align-items-center">';
                $html .= '<div class="ms-5">';
                if (isset($item->customer->name)) {
                    $html .= '<a href="' . url('pos') . '/' . $item->id . '/show' . '" class="text-gray-800 text-hover-primary fs-5 fw-bold">' . $item->customer->name . '</a>';
                } else {
                    $html .= '<a href="' . url('pos') . '/' . $item->id . '/show' . '" class="text-gray-800 text-hover-primary fs-5 fw-bold">Pelanggan Umum</a>';
                }
                $html .= '<br><span class="text-muted d-block fs-7">Total Rp' . tonumberround($item->total) . '</span>';
                $html .= '<span class="text-muted d-block fs-7">Sisa Rp' . tonumberround($item->total_due) . '</span>';
                $html .= '</div>';
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
                $html = '<span class="text-muted d-block fs-8">' . date('d M Y H:i', strtotime($item->created_at)) . '</span>';
                if ($item->status == 'paid') {
                    $html .= '<span class="badge badge-light-success">Paid</span>';
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
                                <a class="dropdown-item" href="' . route('pos.printPayment', $item->id) . '">
                                    <i class="fa fa-receipt"></i>
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
