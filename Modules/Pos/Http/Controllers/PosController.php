<?php

namespace Modules\Pos\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pos\Entities\PosDetailModel;
use Modules\Pos\Entities\PosModel;
use Modules\Pos\Entities\Payment;
use Modules\Crm\Entities\CustomerTier;
use Modules\Transaction\Entities\ProductionParcelDetail;
use Modules\Crm\Entities\SettingExp;
use Modules\Master\Entities\Product;
use Modules\Pos\Entities\SettingNota;
use Modules\Crm\Entities\Deposito;
use Yajra\DataTables\Facades\DataTables;
use Modules\Crm\Entities\CustomerDeposito;
use Illuminate\Support\Str;
use Modules\Transaction\Entities\Production;
use Modules\Transaction\Entities\ProductionDetail;
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
        $data['data'] = null;
        $data['detail'] = null;
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
        $data['parcelDetail'] = ProductionParcelDetail::with('product')->where('pos_id', $id)->get();
        $data['setting'] = SettingNota::first();
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
        $data['alpinejs'] = true;
        $data['data'] = PosModel::with('customer', 'customer.customerTier', 'courier')->findOrFail($id);
        $data['detail'] = PosDetailModel::with('product', 'parcel', 'product.unit', 'product.productionParcelDetails', 'product.productionParcelDetails.product')->where('pos_id', $id)->get();
        $data['invoice_number'] = $data['data']->invoice_number;
        return view('pos::pos.create2', $data);
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
            Payment::where('pos_id', $id)->delete();

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
            // 'payment_id' => 'required|exists:payment_method,id',
            'payments' => 'required|array',
            'total_payment' => 'required|numeric|min:1',
            'customer_id' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            // Simpan ke tabel pembayaran
            $paymentNames = collect($data['payments'])->pluck('payment_name')->toArray();
            $paymentIds = collect($data['payments'])->pluck('payment_id')->toArray();
            $paymentAmounts = collect($data['payments'])->pluck('amount')->toArray();

            $payment = new Payment([
                'uuid' => Str::uuid(),
                'date' => $data['date'],
                'nota_number' => date('YmdHis'),
                'pos_id' => $data['transaction_id'],
                'branch_id' => $data['branch_id'],
                // 'account_id' => $data['account_id'],
                'payment_method' => json_encode($paymentNames),
                'payment_method_id' => json_encode($paymentIds),
                'payment_amount' => json_encode($paymentAmounts),
                'total' => $data['total_payment'],
                'created_by' => Auth::user()->id_user,
            ]);
            // dd($payment);
            $payment->save();

            $deposito = Deposito::where('customer_id', $data['customer_id'])->first();
            $voucher = $deposito->voucher ?? 0;
            PosModel::where("id", $data['transaction_id'])->update([
                'voucher' => $voucher,
                'voucher_qty' => 1,
                'deposito_id' => $deposito->id ?? null,
            ]);

            $totalPayment = Payment::where('pos_id', $data['transaction_id'])
                ->sum('total');

            $pos = PosModel::findOrFail($data['transaction_id']);
            $total = $pos->total - $pos->voucher;
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
            // $pos->ongkir_status = 'delivered';
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
            'parcel' => 'nullable|array',
            'jus' => 'nullable|array',
            'subtotal' => 'required|numeric',
            'discount' => 'required|numeric',
            'ongkir' => 'required|numeric',
            'discount_ongkir' => 'required|numeric',
            'total' => 'required|numeric',
            'status' => 'nullable|in:draft,paid,debt,temp,pending',
            'process_status' => 'nullable|in:none,pending,done',
            'ongkir_date' => 'nullable|date',
            'ongkir_time' => 'nullable',
            'note' => 'nullable',
            'courier_id' => 'nullable',
            'ongkir_address' => 'nullable',
            'kemasan_price' => 'nullable|numeric',
            'branch_id' => 'nullable',
        ]);

        try {
            $userId = Auth::id();
            DB::beginTransaction();
            $cek = PosModel::where('invoice_number', $data['invoice_number'])->first();
            if ($cek) {
                $pos = PosModel::find($cek->id);
                $posDetail = PosDetailModel::where('pos_id', $cek->id);
                $posDetail = $posDetail->where('parcel_id', '!=', null)->get();
                foreach ($posDetail as $key => $value) {
                    $productId = $value->product_id;
                    $product = Product::find($productId);
                    $product->delete();
                }
                PosDetailModel::where('pos_id', $cek->id)->delete();
                $pos->delete();
            }
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
                'ongkir_discount' => $data['discount_ongkir'] ?? 0,
                'ongkir_date' => $data['ongkir_date'] ?? null,
                'ongkir_time' => $data['ongkir_time'] ?? null,
                'status' => $data['status'] ?? 'draft',
                'process_status' => $data['process_status'] ?? 'none',
                'process_date' => date('Y-m-d H:i:s'),
                'note' => $data['note'] ?? null,
                'created_by' => $userId,
                'courier_id' => $data['courier_id'] ?? null,
                'ongkir_address' => $data['ongkir_address'] ?? null,
                'branch_id' => $data['branch_id'] ?? null,
            ]);
            $pos->save();

            // Simpan item transaksi
            $transaksiId = $pos->id;
            $settingExp = SettingExp::first();
            foreach ($data['items'] as $item) {
                if (is_numeric($item['id'])) {
                    PosDetailModel::insert([
                        'pos_id' => $transaksiId,
                        'product_id' => $item['id'],
                        'price' => $item['price'],
                        'quantity' => $item['qty'],
                        'discount' => $item['discount'] ?? 0,
                        'subtotal' => $item['total_input'],
                        'hpp' => $item['hpp'] ?? 0,
                        'exp' => $item['price'] - $item['hpp'],
                        'exp_value' => ($item['price'] - $item['hpp']) * $settingExp->value_exp,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'type' => 'product',
                        'created_by' => $userId,
                    ]);
                }
            }

            if (isset($data['parcel'])) {
                foreach ($data['parcel'] as $key => $value) {
                    $parcel = $value;
                    $productNameBase = $value['kemasan'] . formatRibuanToK(preg_replace('/[^0-9]/', '', $parcel['budget']));
                    $productDescription = 'Parcel ' . $parcel['kemasan'] . '-' . formatRibuanToK(preg_replace('/[^0-9]/', '', $parcel['budget']));
                    $product = new Product([
                        'name' => Product::generateProductName($productNameBase),
                        'description' => $productDescription,
                        'price' => preg_replace('/[^0-9]/', '', $parcel['budget']),
                        'product_unit' => 3,
                        'status' => 'no-receipt',
                        'tipe' => 'parcel',
                        'hpp' => preg_replace('/[^0-9]/', '', $parcel['hpp']),
                        'fee' => preg_replace('/[^0-9]/', '', $parcel['fee']),
                        'created_by' => $userId,
                    ]);
                    $product->save();
                    PosDetailModel::insert([
                        'pos_id' => $transaksiId,
                        'parcel_id' => $parcel['kemasanId'] ?? Product::where('name', $parcel['kemasan'])->first()->id,
                        'product_id' => $product->id,
                        'price' => $product->price,
                        'quantity' => $parcel['qty'],
                        'discount' => 0,
                        'subtotal' => $product->price,
                        'kemasan_price' => isset($parcel['kemasanPrice']) ? preg_replace('/[^0-9]/', '', $parcel['kemasanPrice']) : Product::where('name', $parcel['kemasan'])->first()->price,
                        'hpp' => $product->hpp,
                        'exp' => $product->price - $product->hpp,
                        'exp_value' => ($product->price - $product->hpp) * $settingExp->value_exp,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'type' => 'parcel',
                        'created_by' => $userId,
                    ]);
                    foreach ($value['data'] as $item) {
                        ProductionParcelDetail::insert([
                            'production_id' => $product->id,
                            'pos_id' => $transaksiId,
                            'product_id' => $item['product'],
                            'quantity' => $item['qty'] * $value['qty'],
                        ]);
                    }
                }
            }

            if (isset($data['jus'])) {
                foreach ($data['jus'] as $key => $value) {
                    $production = new Production([
                        'production_number' => Production::getOrderNumber(),
                        'product_id' => $value['productId'],
                        'production_date' => now(),
                        'status' => 'complete',
                        'created_by' => Auth::user()->id_user,
                        'quantity' => $value['qty'],
                        'staff_id' => Auth::user()->id_user,
                    ]);
                    $production->save();
                    if (isset($value['product_receipt_id'])) {
                        foreach ($value['product_receipt_id'] as $key => $productReceiptId) {
                            $productionDetail = new ProductionDetail([
                                'production_id' => $production->id,
                                'product_id' => $productReceiptId,
                                'quantity' => $value['product_receipt_qty'][$key],
                            ]);
                            $productionDetail->save();
                        }
                    }

                    PosDetailModel::insert([
                        'pos_id' => $transaksiId,
                        'product_id' => $value['productId'],
                        'price' => $value['price'],
                        'quantity' => $value['qty'],
                        'discount' => $value['discount'],
                        'subtotal' => $value['price'] * $value['qty'],
                        'hpp' => $value['hpp'],
                        'exp' => $value['price'] - $value['hpp'],
                        'exp_value' => ($value['price'] - $value['hpp']) * $settingExp->value_exp,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'type' => 'product',
                        'created_by' => $userId,
                    ]);
                }
            }

            // dd($request->all());

            
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
        $data['tier'] = CustomerTier::where('customer_id', $data['data']->customer_id)->first();
        $data['deposito'] = CustomerDeposito::where('customer_id', $data['data']->customer_id)->where('quantity', '>', 0)->first();
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
        $data['deposito'] = CustomerDeposito::where('customer_id', $data['data']->customer_id)->where('quantity', '>', 0)->first();
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

    public function cekNotaDraft($id)
    {
        $data['data'] = PosModel::with('customer', 'user')->where('uuid', $id)->first();
        $data['listPayment'] = Payment::with('paymentMethod', 'pos')->where('pos_id', $id)->get();
        $data['setting'] = SettingNota::first();
        $data['detail'] = PosDetailModel::with('product')->where('pos_id', $data['data']->id)->get();
        $data['tier'] = CustomerTier::where('customer_id', $data['data']->customer_id)->first();
        // dd($data);
        return view('pos::pos.print2', $data);
    }

    public function get_data(Request $request)
    {
        $query = PosModel::with('customer', 'payment', 'details');
        if ($request->has('status_filter') && $request->status_filter !== 'all') {
            $query = $query->where('status', $request->status_filter);
        }
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        $data = $query->orderBy('id', 'DESC');
        // dd($data);
        return DataTables::of($data)
            ->filter(function ($q) use ($request) {
                $search = trim($request->input('search.value'));

                if (!empty($search)) {
                    $q->where(function ($sub) use ($search) {
                        $sub->whereHas('customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('name', 'LIKE', "%{$search}%");
                        });
                        $possibleDates = [];
                        $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d', 'd M Y', 'd F Y', 'd/m/Y H:i', 'd-m-Y H:i'];
                        foreach ($formats as $format) {
                            $date = \DateTime::createFromFormat($format, $search);
                            if ($date) {
                                $possibleDates[] = $date->format('Y-m-d');
                                break;
                            }
                        }
                        if (!empty($possibleDates)) {
                            foreach ($possibleDates as $dateStr) {
                                $sub->orWhereDate('date', $dateStr);
                            }
                        }
                        $sub->orWhere('status', 'LIKE', "%{$search}%");
                    });
                }
            }, true)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                $hasParcel = $item->details->contains(fn($detail) => isset($detail->product) && $detail->type === 'parcel');
                $iconHtml = $hasParcel ? '<i class="bi bi-box-seam text-success" title="Ada Parcel"></i>' : '';
                $html = '<div class="d-flex align-items-center">';
                $html .= '<div class="ms-5">';
                if (isset($item->customer->name)) {
                    $html .= $iconHtml . ' <a href="' . url('pos') . '/show' . '/' . $item->id . '" class="text-gray-800 text-hover-primary fs-5 fw-bold">' . $item->customer->name . '</a> ';
                } else {
                    $html .= $iconHtml . ' <a href="' . url('pos') . '/show' . '/' . $item->id . '" class="text-gray-800 text-hover-primary fs-5 fw-bold">Pelanggan Umum</a> ';
                }
                $html .= '<br><span class="text-muted d-block fs-7">Total Rp' . tonumberround($item->total) . '</span>';
                $html .= '<span class="text-muted d-block fs-7">Sisa Rp' . tonumberround($item->total_due - ($item->voucher ?? 0)) . '</span>';
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
                $date = date('d M Y H:i', strtotime($item->created_at));
                $badge = match ($item->status) {
                    'paid' => '<span class="badge badge-light-success">Paid</span>',
                    'draft' => '<span class="badge badge-light-danger">Draft</span>',
                    default => '<span class="badge badge-light-warning">' . e($item->status) . '</span>'
                };

                return "<span class=\"text-muted d-block fs-8\">{$date}</span>{$badge}";
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
                                <a class="dropdown-item" href="' . route('pos.show', $item->id) . '">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </li>';
                if (in_array($item->status, ['temp', 'draft'])) {
                    $html .= '
                            <li>
                                <a class="dropdown-item" href="' . route('pos.edit', $item->id) . '">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </li>';
                }
                if (!in_array($item->status, ['paid'])) {
                    $html .= '
                            <li>
                                <a class="dropdown-item" href="' . route('pos.payment', $item->id) . '">
                                    <i class="bi bi-cash-stack"></i>
                                </a>
                            </li>';
                }
                $html .= '
                            <li>
                                <a class="dropdown-item" href="' . route('pos.printPayment', $item->id) . '">
                                    <i class="fa fa-receipt"></i>
                                </a>
                            </li>';
                if (!in_array($item->status, ['paid', 'debt']) || Session('role')['id_role'] == 1) {
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
