<?php

namespace Modules\Transaction\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Transaction\Entities\Production;
use Modules\Transaction\Entities\ProductionDetail;
use Modules\Master\Entities\Product;
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
        return view('transaction::production.create');
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
            'product_id' => 'required|exists:products,id',
            'submit_type' => 'required|in:draft,posting',
            'production_date' => 'required|date',
            'product_receipt_id' => 'required|array',
            'quantity' => 'required',
            'staff_id' => 'nullable|exists:staff,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $production = new Production();
            $production->production_number = Production::getOrderNumber();
            $production->product_id = $request->product_id;
            $production->production_date = $request->production_date;
            $production->status = $request->submit_type;
            $production->created_by = Auth::user()->id_user;
            $production->quantity = $request->quantity;
            $production->staff_id = $request->staff_id;
            $production->save();

            $productionId = $production->id;
            foreach ($request->product_receipt_id as $key => $product) {
                $productionDetail = new ProductionDetail();
                $productionDetail->production_id = $productionId;
                $productionDetail->product_id = $product;
                $productionDetail->quantity = $request->ingredients_quantity[$key];
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
        $data['data'] = Production::find($id);
        $data['production_detail'] = ProductionDetail::with('products')->where('production_id', $id)->get();
        $data['selectedProduct'] = Product::find($data['data']->product_id);
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
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'submit_type' => 'required|in:draft,posting',
            'production_date' => 'required|date',
            'product_receipt_id' => 'required|array',
            'quantity' => 'required',
            'staff_id' => 'nullable|exists:staff,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $production = Production::findOrFail($id);
            $production->product_id = $request->product_id;
            $production->production_date = $request->production_date;
            $production->status = $request->submit_type;
            $production->created_by = Auth::user()->id_user;
            $production->quantity = $request->quantity;
            $production->staff_id = $request->staff_id;
            $production->save();

            $productionId = $id;
            // Hapus detail produksi yang lama
            ProductionDetail::where('production_id', $productionId)->delete();
            // Simpan detail produksi yang baru
            if (empty($request->product_receipt_id)) {
                return redirect()->back()->withInput($request->all())
                    ->with('error', 'Detail produksi tidak boleh kosong');
            }

            // dd($request->product_receipt_id);
            foreach ($request->product_receipt_id as $key => $product) {
                $productionDetail = new ProductionDetail();
                $productionDetail->production_id = $productionId;
                $productionDetail->product_id = $product;
                $productionDetail->quantity = $request->ingredients_quantity[$key];
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

    public function get_data(Request $request)
    {
        $data = Production::with('products')->get();
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
                    return '<span class="badge badge-light-primary">Posting</span>';
                } elseif ($item->status == 'complete') {
                    return '<span class="badge badge-light-success">Complete</span>';
                } elseif ($item->status == 'draft') {
                    return '<span class="badge badge-light-warning">Draft</span>';
                } else {
                    return '<span class="badge badge-light-danger">Unknown</span>';
                }
            })
            ->addColumn('status_raw', function ($item) {
                return $item->status;
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
                                <a class="dropdown-item" href="' . route('production.edit', $item->id) . '">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="' . route('production.edit', $item->id) . '">
                                    <i class="bi bi-pencil"></i>
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
            ->addColumn('production_id', function ($item) {
                return $item->id;
            })
            ->rawColumns(['name', 'action', 'status', 'address'])
            ->make(true);
    }
}
