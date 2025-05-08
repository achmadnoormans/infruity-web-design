<?php

namespace Modules\Transaction\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Product;
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
        $data['product'] = DB::table('sortir_view')->where('sortir_view.id', $id)->join('products', 'sortir_view.product_id', '=', 'products.id')->first();
        $data['productChild'] = Product::where('parent_id', $id)->get();
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

    public function get_data(Request $request)
    {
        $data = DB::table('sortir_view')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($product) {
                $html = '
                    <div class="d-flex align-items-center">';
                if (isset($product->image)) {
                    $url = asset('storage/' . $product->image);
                    $html .= '<img src="' . $url . '" alt="Product Image" width="50">';
                } else {
                    $html .= '<a href="javascript:void(0)" class="symbol symbol-50px">
                            <span class="symbol-label" style="background-image:url(assets/media/svg/files/blank-image.svg);"></span>
                        </a>';
                }
                $html .= '<div class="ms-5">
                            <a href="' . url('/products') . '" class="text-gray-800 text-hover-primary fs-5 fw-bold" 
                               data-kt-ecommerce-product-filter="product_name">
                                ' . e($product->name) . '
                            </a>
                        </div>
                    </div>
                ';
                return $html;
            })
            ->addColumn('action', function ($item) {
                return '
                    <a href="' . route('sortir.show', $item->id) . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" title="View">
                        <i class="fa fa-eye"></i>
                    </a>
                ';
            })
            ->rawColumns(['name', 'action'])
            ->make(true);
    }
}
