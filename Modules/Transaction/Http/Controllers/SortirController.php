<?php

namespace Modules\Transaction\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Product;
use Modules\Transaction\Entities\WholesaleProduct;
use Modules\Transaction\Entities\StockIn;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Facades\Excel;

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
        $data['product'] = WholesaleProduct::findOrFail($id);
        $data['productChild'] = Product::where('parent_id', $data['product']->product_id)->get();
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
            'wholesale_product_id' => 'required|exists:products,id',
            'quantity' => 'required|array',
        ])->validate();

        try {
            DB::beginTransaction();
            $wholesaleProduct = WholesaleProduct::findOrFail($request->wholesale_product_id);
            $quantity = $wholesaleProduct->quantity;
            $hpp = $wholesaleProduct->hpp;
            $avgPrice = $hpp / $quantity;

            foreach ($request->quantity as $key => $value) {
                $stockIn = new StockIn();
                $stockIn->code = 'wholesale_product';
                $stockIn->reference_id = $request->wholesale_product_id;
                $stockIn->date = date('Y-m-d');
                $stockIn->product_id = $key;
                $stockIn->quantity = $value;
                $stockIn->avg_price = $avgPrice;
                $stockIn->created_by = Auth::user()->id;
                $stockIn->save();
            }

            $wholesaleProduct->quantity = $quantity - array_sum($request->quantity);
            $wholesaleProduct->updated_by = Auth::user()->id;
            $wholesaleProduct->save();
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
        $data = WholesaleProduct::join('wholesale', 'wholesale.id', '=', 'wholesale_product.wholesale_id')
        ->where('wholesale.status', 'complete')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                $html = '
                    <div class="d-flex align-items-center">';
                if (isset($item->product->image)) {
                    $url = asset('storage/' . $item->product->image);
                    $html .= '<img src="' . $url . '" alt="Product Image" width="50">';
                } else {
                    $html .= '<a href="javascript:void(0)" class="symbol symbol-50px">
                            <span class="symbol-label" style="background-image:url(assets/media/svg/files/blank-image.svg);"></span>
                        </a>';
                }
                $html .= '<div class="ms-5">
                            <a href="' . url('/products') . '" class="text-gray-800 text-hover-primary fs-5 fw-bold" 
                               data-kt-ecommerce-product-filter="product_name">
                                ' . e($item->product->name) . '
                            </a>
                        </div>
                    </div>
                ';
                return $html;
            })
            ->addColumn('order_number', function ($item) {
                return '<a href="' . route('wholesale.show', $item->wholesale_id) . '" class="text-gray-800 text-hover-primary fs-5 fw-bold" data-bs-toggle="tooltip" title="Show Wholesale">'. '#' . $item->wholesale->order_number .'</a>';
            })
            ->addColumn('action', function ($item) {
                return '
                    <a href="' . route('sortir.show', $item->id) . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="View">
                        <i class="fa fa-eye"></i>
                    </a>
                ';
            })            
            ->rawColumns(['name', 'action', 'order_number'])
            ->make(true);
    }
}
