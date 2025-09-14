<?php

namespace Modules\Transaction\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Product;
use Modules\Master\Entities\ProductChild;
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

class SortirController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('transaction::sortir.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('transaction::create');
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
        return view('transaction::edit');
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
        //
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

    public function get_data(Request $request)
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
}
