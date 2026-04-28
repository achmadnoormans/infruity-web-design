<?php
namespace Modules\Pos\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Crm\Entities\CustomerDeposito;
use Modules\Crm\Entities\CustomerTier;
use Modules\Crm\Entities\Deposito;
use Modules\Crm\Entities\SettingExp;
use Modules\Master\Entities\Branch;
use Modules\Master\Entities\Product;
use Modules\Master\Entities\UserBranch;
use Modules\Pos\Entities\OrderBook;
use Modules\Pos\Entities\Payment;
use Modules\Pos\Entities\PosDetailModel;
use Modules\Pos\Entities\PosModel;
use Modules\Pos\Entities\SettingNota;
use Modules\Transaction\Entities\ProductHppRunning;
use Modules\Transaction\Entities\Production;
use Modules\Transaction\Entities\ProductionDetail;
use Modules\Transaction\Entities\ProductionParcelDetail;
use Yajra\DataTables\Facades\DataTables;

class PosController extends Controller
{
    use \App\Traits\HasAccessControl;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if ($denied = $this->requireAccess('pos.index')) {
            return $denied;
        }

        $data['alpinejs'] = true;
        $userBranches = UserBranch::getUserBranch();
        $data['branches'] = Branch::whereIn('id', $userBranches)->get();

        // Cek product_stock yang kosong pada branch yang dimiliki user
        $emptyStockProducts = DB::table('product_stock')
            ->whereIn('branch_id', $userBranches)
            ->where('stock_available', '<', 0)
            ->get();

        $data['hasEmptyStock'] = $emptyStockProducts->isNotEmpty();
        $data['emptyStockCount'] = $emptyStockProducts->count();
        $data['emptyStockProducts'] = $emptyStockProducts->take(10); // Ambil 10 produk pertama

        return view('pos::pos.index2', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if ($denied = $this->requireAccess('pos.create')) {
            return $denied;
        }

        $data['alpinejs'] = true;

        // Cek apakah ada temp transaksi untuk user ini
        $draft = PosModel::with([
            'customer',
            'courier',
            'branch',
            'branch_proses',
            'details',
            'details.parcel',
            'details.product',
            'details.product.unit',
            'details.product.productionParcelDetails',
            'details.product.productionParcelDetails.product',
            'details.product.productionParcelDetails.product.productBranches',
        ])
            ->where('created_by', Auth::id())
            ->where('status', 'temp')
            ->orderBy('id', 'desc')
            ->first();

        if ($draft) {
            $data['data']           = $draft;
            $data['detail']         = $draft->details;
            $data['invoice_number'] = $draft->invoice_number;
        } else {
            $data['data']           = null;
            $data['detail']         = null;
            $data['invoice_number'] = PosModel::getOrderNumber();
        }

        return view('pos::pos.create2', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if ($denied = $this->requireAccess('pos.store')) {
            return $denied;
        }

        // dd($request->all());
        $request->validate([
            'customer_id'         => 'nullable|exists:customer,id',
            'items'               => 'required|array',
            'items.*.product_id'  => 'required|exists:products,id',
            'items.*.qty'         => 'required|numeric|min:1',
            'items.*.price'       => 'required|numeric|min:0',
            'items.*.discount'    => 'nullable|numeric|min:0',
            'items.*.total_input' => 'required|numeric|min:0',
        ]);

        $sum_discount    = array_sum(array_column($request->items, 'discount'));
        $sum_total_input = array_sum(array_column($request->items, 'total_input'));

        DB::beginTransaction();
        try {

            $userId = Auth::id(); // Ambil user sekali
            $pos    = new PosModel([
                'customer_id' => $request->customer_id,
                'date'        => date('Y-m-d'),
                'total'       => $sum_total_input - $sum_discount,
                'created_by'  => $userId,
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
                    'pos_id'     => $pos->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['qty'],
                    'price'      => $item['price'],
                    'discount'   => $item['discount'] ?? 0,
                    'subtotal'   => $item['total_input'],
                ];
            }
            PosDetailModel::insert($posDetail);

            DB::commit();

            return response()->json([
                'success' => true,
                'id'      => $pos->id,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
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
        if ($denied = $this->requireAccess('pos.show')) {
            return $denied;
        }

        $data['data']         = PosModel::with('customer')->findOrFail($id);
        $data['detail']       = PosDetailModel::with('product')->where('pos_id', $id)->get();
        $data['parcelDetail'] = ProductionParcelDetail::with('product')->where('pos_id', $id)->get();
        $data['setting']      = SettingNota::first();
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
        if ($denied = $this->requireAccess('pos.edit')) {
            return $denied;
        }

        $data['alpinejs']       = true;
        $data['data']           = PosModel::with('customer', 'customer.customerTier', 'courier', 'courierExternal', 'branch', 'branch_proses', 'user')->findOrFail($id);
        $data['detail']         = PosDetailModel::with('product', 'parcel', 'product.unit', 'product.productionParcelDetails', 'product.productionParcelDetails.product', 'product.productionParcelDetails.product.productBranches')->where('pos_id', $id)->get();
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
        if ($denied = $this->requireAccess('pos.update')) {
            return $denied;
        }

        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if ($denied = $this->requireAccess('pos.destroy')) {
            return $denied;
        }

        try {
            DB::beginTransaction();
            $pos = PosModel::findOrFail($id);
            $pos->delete();
            PosDetailModel::where('pos_id', $id)->delete();
            Payment::where('pos_id', $id)->delete();

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

    public function savePayment(Request $request)
    {
        if ($denied = $this->requireAccess('pos.savePayment')) {
            return $denied;
        }

        // dd($request->all());
        $data = $request->validate([
            'date'           => 'required|date',
            'transaction_id' => 'required|exists:pos_transaction,id',
            // 'branch_id' => 'required|exists:branch,id',
            // 'account_id' => 'required|exists:account,id',
            // 'payment_id' => 'required|exists:payment_method,id',
            'payments'       => 'required|array',
            'total_payment'  => 'required|numeric|min:0',
            'customer_id'    => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            // Simpan ke tabel pembayaran
            $paymentNames   = collect($data['payments'])->pluck('payment_name')->toArray();
            $paymentIds     = collect($data['payments'])->pluck('payment_id')->toArray();
            $paymentAmounts = collect($data['payments'])->pluck('amount')->toArray();

            $payment = new Payment([
                'uuid'              => Str::uuid(),
                'date'              => $data['date'],
                'nota_number'       => date('YmdHis'),
                'pos_id'            => $data['transaction_id'],
                // 'branch_id' => $data['branch_id'],
                // 'account_id' => $data['account_id'],
                'payment_method'    => json_encode($paymentNames),
                'payment_method_id' => json_encode($paymentIds),
                'payment_amount'    => json_encode($paymentAmounts),
                'total'             => $data['total_payment'],
                'created_by'        => Auth::user()->id_user,
            ]);
            // dd($payment);
            $payment->save();

            $deposito = Deposito::where('customer_id', $data['customer_id'])->first();
            $voucher  = $deposito->voucher ?? 0;
            PosModel::where("id", $data['transaction_id'])->update([
                'voucher'     => $voucher,
                'voucher_qty' => 1,
                'deposito_id' => $deposito->id ?? null,
            ]);

            $totalPayment = Payment::where('pos_id', $data['transaction_id'])
                ->sum('total');

            $pos       = PosModel::findOrFail($data['transaction_id']);
            $total     = $pos->total - $pos->voucher;
            $pos->paid = $totalPayment;
            if ($totalPayment > $total) {
                $lastPayment         = Payment::findOrFail($payment->id);
                $lastPayment->return = ($totalPayment - $total);
                $lastPayment->save();
            } else {
                $lastPayment            = Payment::findOrFail($payment->id);
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
                'pos'     => $pos,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function listPayment($id)
    {
        if ($denied = $this->requireAccess('pos.listPayment')) {
            return $denied;
        }

        $payments = Payment::with('paymentMethod')->where('pos_id', $id)
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($payments);
    }

    public function showReceipt($id)
    {
        if ($denied = $this->requireAccess('pos.receipt')) {
            return $denied;
        }

        $data['data']   = PosModel::with('customer')->findOrFail($id);
        $data['detail'] = PosDetailModel::with('product')->where('pos_id', $id)->get();
        // dd($data);
        return view('pos::pos.receipt2', $data);
    }

    public function saveTransaction(Request $request)
    {
        if ($denied = $this->requireAccess('pos.receipt')) {
            return $denied;
        }

        // dd($request->all());
        $data = $request->validate([
            // 'customer_id' => 'nullable|exists:customer,id',
            'customer_id'       => 'nullable',
            'date'              => 'required|date',
            'invoice_number'    => 'nullable',
            'items'             => 'required|array',
            'parcel'            => 'nullable|array',
            'jus'               => 'nullable|array',
            'subtotal'          => 'required|numeric',
            'discount'          => 'nullable|numeric',
            'ongkir'            => 'required|numeric',
            'discount_ongkir'   => 'required|numeric',
            'total'             => 'required|numeric',
            'status'            => 'nullable|in:draft,paid,debt,temp,pending',
            'process_status'    => 'nullable|in:none,pending,done',
            'ongkir_date'       => 'nullable|date',
            'ongkir_time'       => 'nullable',
            'note'              => 'nullable',
            'courier_id'        => 'nullable',
            'courier_type'      => 'nullable',
            'ongkir_address'    => 'nullable',
            'kemasan_price'     => 'nullable|numeric',
            'branch_id'         => 'nullable',
            'branch_process_id' => 'nullable',
        ]);

        try {
            $userId = Auth::id();
            $isTempSave = ($data['status'] ?? null) === 'temp';
            DB::beginTransaction();
            $invoiceNumber = $data['invoice_number'] ?? null;
            $pos = null;

            if (!empty($invoiceNumber)) {
                $pos = PosModel::where('created_by', $userId)
                    ->where('invoice_number', $invoiceNumber)
                    ->lockForUpdate()
                    ->first();
            }

            if ($pos) {
                $this->clearExistingPosRelations($pos->id);
                $originalStatus = $pos->status;
            } else {
                $pos = new PosModel();
                $pos->uuid = Str::uuid();
                $originalStatus = null;
            }

            $statusToSave = $data['status'] ?? 'draft';
            if ($pos->exists && $originalStatus && $originalStatus !== 'temp' && $statusToSave === 'temp') {
                $statusToSave = $originalStatus;
            }

            $pos->fill([
                'customer_id'       => $data['customer_id'],
                'date'              => $data['date'],
                'invoice_number'    => $invoiceNumber,
                'subtotal'          => $data['subtotal'],
                'total'             => $data['total'],
                'discount'          => $data['discount'],
                'ongkir'            => $data['ongkir'],
                'ongkir_discount'   => $data['discount_ongkir'] ?? 0,
                'ongkir_date'       => $data['ongkir_date'] ?? null,
                'ongkir_time'       => $data['ongkir_time'] ?? null,
                'status'            => $statusToSave,
                'process_status'    => $data['process_status'] ?? 'none',
                'process_date'      => date('Y-m-d H:i:s'),
                'note'              => $data['note'] ?? null,
                'created_by'        => $userId,
                'courier_id'        => $data['courier_id'] ?? null,
                'courier_type'      => $data['courier_type'] ?? null,
                'ongkir_address'    => $data['ongkir_address'] ?? null,
                'branch_id'         => $data['branch_id'] ?? null,
                'branch_process_id' => $data['branch_process_id'] ?? null,
            ]);
            $pos->save();
            $this->deleteDuplicatePosDrafts($userId, $invoiceNumber, $pos->id);

            // Simpan item transaksi
            $transaksiId = $pos->id;
            $settingExp  = SettingExp::first();
            $totalPrice  = $this->sumTotalPrice($data['items']);
            foreach ($data['items'] as $item) {
                if (is_numeric($item['id'])) {
                    $itemTotal   = $item['total_input'] ?? ($item['price'] * $item['qty']);
                    $prosentase  = $totalPrice > 0 ? round(($itemTotal / $totalPrice) * 100, 2) : 0;
                    $posDiscount = $pos->discount * $prosentase / 100;
                    $product     = Product::find($item['id']);

                    // Ambil parent/child dari product yang dipilih
                    $childProducts = $product->childProducts()->get();
                    $parentId      = $product->getParentId();

                    // Jika product memiliki parent, gunakan parent_id untuk HPP, jika tidak gunakan product itu sendiri
                    $hppProductId = $parentId ?? $product->id;
                    $productHpp   = ProductHppRunning::where('product_id', $hppProductId)
                        ->latest()
                        ->first();
                    $currentStock = $productHpp ? $productHpp->qty_berjalan : 0;
                    $hppValue     = $productHpp ? ($productHpp->hpp_berjalan ?? 0) : 0;

                    $posDetail = PosDetailModel::create([
                        'pos_id'               => $transaksiId,
                        'product_id'           => $item['id'],
                        'price'                => $item['price'],
                        'quantity'             => $item['qty'],
                        'discount'             => $item['discount'] ?? 0,
                        'subtotal'             => $item['total_input'],
                        'price_after_discount' => $item['price'] - $posDiscount,
                        'exp'                  => $item['price'] - $hppValue,
                        'exp_value'            => ($item['price'] - $hppValue) * $settingExp->value_exp,
                        'created_at'           => now(),
                        'updated_at'           => now(),
                        'type'                 => 'product',
                        'created_by'           => $userId,
                    ]);

                    if ($currentStock <= 0) {
                        $posDetail->update([
                            'hpp'           => 0,
                            'debt_quantity' => 0,
                            'subtotal_hpp'  => 0,
                        ]);
                    } else {
                        if ($currentStock < $posDetail->quantity) {
                            $posDetail->update([
                                'hpp'           => 0,
                                'debt_quantity' => $posDetail->quantity - $currentStock,
                                'subtotal_hpp'  => ($productHpp->hpp_berjalan ?? 0) * $currentStock,
                            ]);
                        } else {
                            $posDetail->update([
                                'hpp'           => $productHpp->hpp_berjalan ?? 0,
                                'debt_quantity' => 0,
                                'subtotal_hpp'  => ($productHpp->hpp_berjalan ?? 0) * $posDetail->quantity,
                            ]);
                        }
                    }
                }
            }

            if (isset($data['parcel'])) {
                foreach ($data['parcel'] as $key => $value) {
                    $parcel             = $value;
                    $kemasanProduct     = null;
                    if (!empty($parcel['kemasan'])) {
                        $kemasanProduct = Product::where('name', $parcel['kemasan'])->first();
                    }
                    $productNameBase    = $value['kemasan'] . formatRibuanToK(preg_replace('/[^0-9]/', '', $parcel['budget']));
                    $productDescription = 'Parcel ' . $parcel['kemasan'] . '-' . formatRibuanToK(preg_replace('/[^0-9]/', '', $parcel['budget']));
                    $product            = new Product([
                        'name'         => Product::generateProductName($productNameBase),
                        'description'  => $productDescription,
                        'price'        => preg_replace('/[^0-9]/', '', $parcel['budget']),
                        'product_unit' => 3,
                        'status'       => 'no-receipt',
                        'tipe'         => 'parcel',
                        'hpp'          => preg_replace('/[^0-9]/', '', $parcel['hpp']),
                        'fee'          => preg_replace('/[^0-9]/', '', $parcel['fee']),
                        'created_by'   => $userId,
                    ]);
                    $product->save();
                    PosDetailModel::insert([
                        'pos_id'        => $transaksiId,
                        'parcel_id'     => !empty($parcel['kemasanId']) ? $parcel['kemasanId'] : ($kemasanProduct->id ?? null),
                        'product_id'    => $product->id,
                        'price'         => $product->price,
                        'quantity'      => $parcel['qty'],
                        'discount'      => 0,
                        'subtotal'      => $product->price,
                        'kemasan_price' => isset($parcel['kemasanPrice']) ? preg_replace('/[^0-9]/', '', $parcel['kemasanPrice']) : ($kemasanProduct->price ?? 0),
                        'hpp'           => $product->hpp,
                        'exp'           => $product->price - $product->hpp,
                        'exp_value'     => ($product->price - $product->hpp) * $settingExp->value_exp,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                        'type'          => 'parcel',
                        'created_by'    => $userId,
                    ]);
                    foreach ($value['data'] as $item) {
                        $parcelQtyPerSet = (float) ($item['qty'] ?? 0);
                        $parcelLinePrice = (float) ($item['price'] ?? 0);
                        $parcelBasePrice = (float) ($item['priceAwal'] ?? 0);

                        if ($parcelBasePrice <= 0 && $parcelQtyPerSet > 0) {
                            $parcelBasePrice = $parcelLinePrice / $parcelQtyPerSet;
                        }

                        ProductionParcelDetail::insert([
                            'production_id' => $product->id,
                            'pos_id'        => $transaksiId,
                            'product_id'    => $item['product'],
                            'quantity'      => $parcelQtyPerSet * $value['qty'],
                            'price'         => $parcelLinePrice,
                            'price_awal'    => $parcelBasePrice,
                        ]);
                    }
                }
            }

            if (isset($data['jus'])) {
                foreach ($data['jus'] as $key => $value) {
                    if (!$isTempSave) {
                        $production = new Production([
                            'production_number' => Production::getOrderNumber(),
                            'product_id'        => $value['productId'],
                            'production_date'   => now(),
                            'status'            => 'complete',
                            'created_by'        => Auth::user()->id_user,
                            'quantity'          => $value['qty'],
                            'staff_id'          => Auth::user()->id_user,
                        ]);
                        $production->save();
                        if (isset($value['product_receipt_id'])) {
                            foreach ($value['product_receipt_id'] as $key => $productReceiptId) {
                                $productionDetail = new ProductionDetail([
                                    'production_id' => $production->id,
                                    'product_id'    => $productReceiptId,
                                    'quantity'      => $value['product_receipt_qty'][$key],
                                ]);
                                $productionDetail->save();
                            }
                        }
                    }

                    PosDetailModel::insert([
                        'pos_id'     => $transaksiId,
                        'product_id' => $value['productId'],
                        'price'      => $value['price'],
                        'quantity'   => $value['qty'],
                        'discount'   => $value['discount'],
                        'subtotal'   => $value['price'] * $value['qty'],
                        'hpp'        => $value['hpp'],
                        'exp'        => $value['price'] - $value['hpp'],
                        'exp_value'  => ($value['price'] - $value['hpp']) * $settingExp->value_exp,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'type'       => 'product',
                        'created_by' => $userId,
                    ]);
                }
            }

            if (!$isTempSave && preg_match('/ORD\d+/i', $data['invoice_number'])) {
                $orderBook = OrderBook::where('invoice_number', $data['invoice_number'])->first();
                if ($orderBook) {
                    $orderBook->status = 'done';
                    $orderBook->save();
                }
            }

            // dd($request->all());
            DB::commit();
            DB::disconnect();

            return response()->json([
                'success'      => true,
                'message'      => 'Transaksi berhasil disimpan',
                'transaksi_id' => $transaksiId,
                'invoice_number' => $pos->invoice_number,
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

    private function clearExistingPosRelations(int $posId): void
    {
        $parcelProductIds = PosDetailModel::where('pos_id', $posId)
            ->whereNotNull('parcel_id')
            ->pluck('product_id')
            ->filter()
            ->all();

        if (!empty($parcelProductIds)) {
            Product::whereIn('id', $parcelProductIds)->delete();
        }

        ProductionParcelDetail::where('pos_id', $posId)->delete();
        PosDetailModel::where('pos_id', $posId)->forceDelete();
    }

    private function deleteDuplicatePosDrafts(int $userId, ?string $invoiceNumber, int $keepPosId): void
    {
        if (empty($invoiceNumber)) {
            return;
        }

        $duplicates = PosModel::where('created_by', $userId)
            ->where('invoice_number', $invoiceNumber)
            ->where('id', '!=', $keepPosId)
            ->get();

        foreach ($duplicates as $duplicate) {
            $this->clearExistingPosRelations($duplicate->id);
            $duplicate->forceDelete();
        }
    }

    public function payment($id)
    {
        if ($denied = $this->requireAccess('pos.payment')) {
            return $denied;
        }

        $data['alpinejs'] = true;
        $data['data']     = PosModel::with('customer')->findOrFail($id);
        $data['detail']   = PosDetailModel::with('product')->where('pos_id', $id)->get();
        $data['tier']     = CustomerTier::where('customer_id', $data['data']->customer_id)->first();
        $data['deposito'] = CustomerDeposito::where('customer_id', $data['data']->customer_id)->where('quantity', '>', 0)->first();
        // dd($data);
        return view('pos::pos.payment', $data);
    }

    public function printPayment($id)
    {
        if ($denied = $this->requireAccess('pos.printPayment')) {
            return $denied;
        }

        // $data['data'] = PosModel::with('customer', 'user')->findOrFail($id);
        $data['listPayment'] = Payment::with('paymentMethod', 'pos')->where('pos_id', $id)->get();
        $data['setting']     = SettingNota::first();
        $data['detail']      = PosDetailModel::with('product')->where('pos_id', $id)->get();
        return view('pos::pos.print-list-payment', $data);
    }

    public function printDraftPayment($id)
    {
        if ($denied = $this->requireAccess('pos.printDraftPayment')) {
            return $denied;
        }

        $data['data']        = PosModel::with('customer', 'user')->findOrFail($id);
        $data['listPayment'] = Payment::with('paymentMethod', 'pos')->where('pos_id', $id)->get();
        $data['setting']     = SettingNota::first();
        $data['detail']      = PosDetailModel::with('product')->where('pos_id', $id)->get();
        $data['tier']        = CustomerTier::where('customer_id', $data['data']->customer_id)->first();
        $data['deposito']    = CustomerDeposito::where('customer_id', $data['data']->customer_id)->where('quantity', '>', 0)->first();
        // dd($data);
        return view('pos::pos.print2', $data);
    }

    public function uploadReceipt(Request $request)
    {
        $image = $request->input('image');
        if (! $image) {
            return response()->json(['error' => 'No image'], 400);
        }

        // Pisahkan data:image/png;base64,
        $image_parts  = explode(";base64,", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName     = 'receipt_' . time() . '.png';

        // Simpan di public/storage/receipts
        $filePath = public_path('storage/receipts/' . $fileName);
        file_put_contents($filePath, $image_base64);

        return response()->json([
            'url' => asset('storage/receipts/' . $fileName),
        ]);
    }

    public function paymentNotification($id)
    {
        if ($denied = $this->requireAccess('pos.paymentNotification')) {
            return $denied;
        }

        $data['data']         = Payment::with('paymentMethod', 'pos')->findOrFail($id);
        $data['totalPayment'] = Payment::where('pos_id', $data['data']->pos_id)->sum('total');
        return view('pos::pos.payment-success2', $data);
    }

    public function printNota($id)
    {
        if ($denied = $this->requireAccess('pos.printNota')) {
            return $denied;
        }

        $data['payment'] = Payment::findOrFail($id);
        $data['data']    = PosModel::with('customer', 'user')->findOrFail($data['payment']->pos_id);
        $data['setting'] = SettingNota::first();
        $data['detail']  = PosDetailModel::with('product')->where('pos_id', $data['payment']->pos_id)->get();
        $data['tier']    = CustomerTier::where('customer_id', $data['data']->customer_id)->first();
        // dd($data);
        return view('pos::pos.print2', $data);
    }

    public function cekNota($id)
    {
        if ($denied = $this->requireAccess('pos.cek-nota')) {
            return $denied;
        }

        $data['payment'] = Payment::where('uuid', $id)->first();
        if (isset($data['payment'])) {
            $data['data']    = PosModel::with('customer', 'user')->findOrFail($data['payment']->pos_id);
            $data['setting'] = SettingNota::first();
            $data['detail']  = PosDetailModel::with('product')->where('pos_id', $data['payment']->pos_id)->get();
            $data['tier']    = CustomerTier::where('customer_id', $data['data']->customer_id)->first();
            // dd($data);
            return view('pos::pos.print2', $data);
        } else {
            abort(404);
        }
    }

    public function cekNotaDraft($id)
    {
        if ($denied = $this->requireAccess('pos.cek-nota.draft')) {
            return $denied;
        }

        $data['data'] = PosModel::with('customer', 'user')->where('uuid', $id)->first();
        if (! isset($data['data'])) {
            abort(404);
        }
        $data['listPayment'] = Payment::with('paymentMethod', 'pos')->where('pos_id', $id)->get();
        $data['setting']     = SettingNota::first();
        $data['detail']      = PosDetailModel::with('product')->where('pos_id', $data['data']->id)->get();
        $data['tier']        = CustomerTier::where('customer_id', $data['data']->customer_id)->first();
        // dd($data);
        return view('pos::pos.print2', $data);
    }

    public function get_data(Request $request)
    {
        $userId = auth()->id();
        $query = PosModel::with('customer', 'paymentDetails', 'details', 'branch');
        // ->whereIn('branch_id', UserBranch::getUserBranch());

        $query->where(function ($q) use ($userId) {
            $q->where('status', '!=', 'temp')
              ->orWhere('created_by', $userId);
        });

        if ($request->has('status_filter') && $request->status_filter !== 'all') {
            $query = $query->where('status', $request->status_filter);
        }
        // if ($request->has('cabang_filter') && $request->cabang_filter !== 'all') {
        //     $query->whereHas('paymentDetails', function ($q) use ($request) {
        //         $q->where('branch_id', $request->cabang_filter);
        //     });
        // }
        if ($request->has('cabang_filter') && $request->cabang_filter !== 'all') {
            $query = $query->where('branch_id', $request->cabang_filter);
        }
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        $data = $query->orderBy('id', 'DESC');
        // dd($data);
        return DataTables::of($data)
            ->filter(function ($q) use ($request) {
                $search = trim($request->input('search.value'));

                if (! empty($search)) {
                    $q->where(function ($sub) use ($search) {
                        $sub->whereHas('customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('whatsapp', 'LIKE', "%{$search}%");
                        });
                        $possibleDates = [];
                        $formats       = ['d/m/Y', 'd-m-Y', 'Y-m-d', 'd M Y', 'd F Y', 'd/m/Y H:i', 'd-m-Y H:i'];
                        foreach ($formats as $format) {
                            $date = \DateTime::createFromFormat($format, $search);
                            if ($date) {
                                $possibleDates[] = $date->format('Y-m-d');
                                break;
                            }
                        }
                        if (! empty($possibleDates)) {
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
                $hasParcel  = $item->details->contains(fn($detail) => isset($detail->product) && $detail->type === 'parcel');
                $iconHtml   = $hasParcel ? '<i class="bi bi-box-seam text-success" title="Ada Parcel"></i>' : '';
                $html       = '<div class="d-flex align-items-center">';
                $html      .= '<div class="ms-5">';
                if (isset($item->customer->name)) {
                    $waLast4 = !empty($item->customer->whatsapp) ? ' (' . substr($item->customer->whatsapp, -4) . ')' : '';
                    $html .= $iconHtml . ' <a href="' . url('pos') . '/show' . '/' . $item->id . '" class="text-gray-800 text-hover-primary fs-5 fw-bold">' . $item->customer->name . $waLast4 . '</a> ';
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
                $date  = date('d M Y', strtotime($item->date)) . ' ' . date('H:i', strtotime($item->created_at));
                $statusLabels = [
                    'paid' => ['label' => 'Lunas', 'class' => 'success'],
                    'draft' => ['label' => 'Pending', 'class' => 'secondary'],
                    'debt' => ['label' => 'Piutang', 'class' => 'danger'],
                    'canceled' => ['label' => 'Dihapus', 'class' => 'dark'],
                    'temp' => ['label' => 'Pending', 'class' => 'secondary'],
                ];
                $statusInfo = $statusLabels[$item->status] ?? ['label' => ucfirst($item->status), 'class' => 'warning'];
                $badge = '<span class="badge badge-light-' . $statusInfo['class'] . '">' . $statusInfo['label'] . '</span>';
                $branchLabel = '';
                if ($item->branch) {
                    $branchLabel = '<span class="badge badge-light-primary ms-1">' . e($item->branch->name) . '</span>';
                }

                return "<span class=\"text-muted d-block fs-8\">{$date}</span>{$badge}{$branchLabel}";
            })
            ->addColumn('action', function ($item) {
                $html  = '';
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
                if (! in_array($item->status, ['paid'])) {
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
                if (! in_array($item->status, ['paid', 'debt']) || Session('role')['id_role'] == 1) {
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

    private function sumTotalPrice(array $items): int
    {
        return collect($items)->sum(function ($item) {
            return $item['total_input'] ?? ($item['price'] * $item['qty']);
        });
    }
}
