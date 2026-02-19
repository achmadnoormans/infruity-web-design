<?php

namespace Modules\Transaction\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Transaction\Entities\StockOpname;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Master\Entities\Branch;
use Modules\Master\Entities\UserBranch;
use Exception;

class StockOpnameController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data['branches'] = Branch::whereIn('id', UserBranch::getUserBranch())->get();
        return view('transaction::stock-opname.index', $data);
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
        // Validasi input
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'branch_id' => 'required|exists:branch,id',
            'date' => 'required|date',
            'real_stock' => 'required|numeric',
        ]);

        // Simpan data ke database
        try {
            DB::beginTransaction();
            $productStock = DB::table('product_stock')
                ->where('id', $validated['product_id'])
                ->first();
            if (!$productStock) {
                return response()->json([
                    'message' => 'Stok produk tidak ditemukan.',
                ], 404);
            }
            $avg_price = $productStock->hpp;
            $stockAvailable = $productStock->stock_available;
            $stock = new StockOpname();
            $stock->code = StockOpname::getOrderNumber();
            $stock->product_id = $validated['product_id'];
            $stock->date = $validated['date'];
            $stock->branch_id = $validated['branch_id'];
            $stock->avg_price = $avg_price ?? 0;
            $stock->stock = $stockAvailable;
            $stock->real_stock = $validated['real_stock'];
            $stock->difference =  (Double)$validated['real_stock'] - (Double)$stockAvailable;
            $stock->created_by = Auth::user()->id_user;
            $stock->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Transaksi gagal disimpan.',
                'data' => $stock
            ], 404);
        }

        // Kirim response JSON
        return response()->json([
            'message' => 'Transaksi berhasil disimpan.',
            'data' => $stock
        ], 201);
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
        $stock = StockOpname::with('product')->findOrFail($id);
        $stock->name = $stock->product->name;
        return response()->json($stock);
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
            $stock = StockOpname::findOrFail($id);
            $stock->delete();
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
        $data = StockOpname::with('product')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                return '<div class="d-flex align-items-center">
                            <div class="ms-5">
                                <a href="' . url('wholesale') . '/' . $item->id . '/show' . '" class="text-gray-800 text-hover-primary fs-5 fw-bold">#' . $item->code . '</a>
                                <br>
                                <span class="text-muted fw-bold d-block fs-7">' . $item->product->name . '</span>
                                <span class="text-muted fw-bold d-block fs-7">' . $item->stock . '->' . $item->real_stock . '</span>
                                <span class="text-success fw-bold d-block fs-7"> Tgl : ' . dateindo($item->date) . '</span>
                            </div>
                        </div>';
            })
            ->addColumn('quantity', function ($item) {
                $class = 'badge badge-light-success';
                if ($item->difference < 0) {
                    $class = 'badge badge-light-danger';
                }
                return '<span class="' . $class . '" data-id="' . $item->id . '" data-value="' . $item->difference . '">' . toNumber($item->difference) . ' ' . $item->product->unit->abbreviation . '</span>';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('products.edit', $row->id);
                $deleteUrl = route('products.destroy', $row->id);
                $name = e($row->name);

                return '
                <div class="dropstart">
                    <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi ' . $name . '">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="viewProduct(' . $row->id . ')">
                                <i class="bi bi-eye"></i>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="editProduct(' . $row->id . ')">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-primary d-flex justify-content-center" href="javascript:void(0)" onclick="deleteProduct(' . $row->id . ')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </li>
                    </ul>
                </div>';

            })
            ->rawColumns(['name', 'quantity', 'action'])
            ->make(true);
    }
}
