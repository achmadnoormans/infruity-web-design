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
    use \App\Traits\HasAccessControl;

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if ($denied = $this->requireAccess('stock-opname.index')) {
            return $denied;
        }

        $userBranches = UserBranch::getUserBranch();
        $data['branches'] = Branch::whereIn('id', $userBranches)->get();

        $emptyStockProducts = DB::table('product_stock')
            ->join('branch', 'product_stock.branch_id', '=', 'branch.id')
            ->select('product_stock.*', 'branch.name as branch_name')
            ->whereIn('product_stock.branch_id', $userBranches)
            ->where('stock_available', '<', 0)
            ->whereNull('is_variant')
            ->get();

        $data['emptyStockData'] = $emptyStockProducts;

        return view('transaction::stock-opname.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if ($denied = $this->requireAccess('stock-opname.create')) {
            return $denied;
        }

        return view('transaction::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if ($denied = $this->requireAccess('stock-opname.store')) {
            return $denied;
        }

        // Validasi input
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'branch_id' => 'required|exists:branch,id',
            'date' => 'required|date',
            'real_stock' => 'required|numeric',
            'note' => 'nullable|string',
        ]);

        // Simpan data ke database
        try {
            DB::beginTransaction();
            $productStock = DB::table('product_stock')
                ->where('id', $validated['product_id'])
                ->where('branch_id', $validated['branch_id'])
                ->first();
            if (!$productStock) {
                return response()->json([
                    'message' => 'Stok produk tidak ditemukan.',
                ], 404);
            }
            $avg_price = $productStock->avg_hpp;
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

            $noteText = $request->input('note') ?: 'Input Stock Fisik Awal';
            DB::table('stock_opname_history')->insert([
                'stock_opname_id' => $stock->id,
                'action' => 'INITIAL',
                'note' => $noteText,
                'real_stock' => $stock->real_stock,
                'difference' => $stock->difference,
                'created_by' => Auth::user()->id_user,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

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
        if ($denied = $this->requireAccess('stock-opname.edit')) {
            return $denied;
        }

        $stock = StockOpname::with('product')->findOrFail($id);
        $stock->name = $stock->product->name;

        // Fetch latest history note
        $latestHistory = DB::table('stock_opname_history')
            ->where('stock_opname_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();
        $stock->note = $latestHistory ? $latestHistory->note : '';

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
        if ($denied = $this->requireAccess('stock-opname.update')) {
            return $denied;
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'branch_id' => 'required|exists:branch,id',
            'date' => 'required|date',
            'real_stock' => 'required|numeric',
            'note' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $stock = StockOpname::findOrFail($id);

            $stock->product_id = $validated['product_id'];
            $stock->date = $validated['date'];
            $stock->branch_id = $validated['branch_id'];
            $stock->real_stock = $validated['real_stock'];
            $stock->difference = (Double)$validated['real_stock'] - (Double)$stock->stock;
            $stock->updated_by = Auth::user()->id_user;
            $stock->save();

            $noteText = $request->input('note') ?: 'Penyesuaian Stok Fisik';
            DB::table('stock_opname_history')->insert([
                'stock_opname_id' => $stock->id,
                'action' => 'UPDATE',
                'note' => $noteText,
                'real_stock' => $stock->real_stock,
                'difference' => $stock->difference,
                'created_by' => Auth::user()->id_user,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Transaksi gagal diperbarui.',
            ], 500);
        }

        return response()->json([
            'message' => 'Transaksi berhasil diperbarui.',
            'data' => $stock
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if ($denied = $this->requireAccess('stock-opname.destroy')) {
            return $denied;
        }

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
        $userBranches = UserBranch::getUserBranch();
        $query = StockOpname::with('product')
            ->leftJoin('users', 'stock_opname.created_by', '=', 'users.id_user')
            ->leftJoin('branch', 'stock_opname.branch_id', '=', 'branch.id')
            ->leftJoin('product_stock', function ($j) {
                $j->on('product_stock.id', '=', 'stock_opname.product_id')
                  ->on('product_stock.branch_id', '=', 'stock_opname.branch_id');
            })
            ->select(
                'stock_opname.*',
                'users.nm_user as creator_name',
                'branch.name as branch_name',
                'product_stock.avg_hpp as avg_hpp_calc'
            )
            ->whereIn('stock_opname.branch_id', $userBranches);

        if ($request->has('cabang_filter') && $request->cabang_filter !== 'all') {
            $query->where('stock_opname.branch_id', $request->cabang_filter);
        }

        $data = $query->get();
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
            ->addColumn('difference_value', function ($item) {
                $hpp = $item->avg_hpp_calc ?? $item->avg_price ?? 0;
                $value = floor((float) $item->difference * (float) $hpp);
                $class = $value < 0 ? 'text-danger' : 'text-success';

                return '<span class="' . $class . '">Rp ' . toNumber($value) . '</span>';
            })
            ->addColumn('percentage', function ($item) {
                $stock = (float) $item->stock;
                $difference = (float) $item->difference;

                if ($stock == 0.0) {
                    return '<span class="text-muted">-</span>';
                }

                $percentage = ($difference / $stock) * 100;
                $class = $percentage < 0 ? 'text-danger' : 'text-success';

                return '<span class="' . $class . '">' . toNumber($percentage) . '%</span>';
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
            ->rawColumns(['name', 'quantity', 'difference_value', 'percentage', 'action'])
            ->make(true);
    }

    public function get_history($id)
    {
        $stockOpname = StockOpname::findOrFail($id);
        
        $history = DB::table('stock_opname_history')
            ->leftJoin('users', 'stock_opname_history.created_by', '=', 'users.id_user')
            ->select('stock_opname_history.*', 'users.nm_user as creator_name')
            ->where('stock_opname_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        if ($history->isEmpty()) {
            $creator = DB::table('users')->where('id_user', $stockOpname->created_by)->first();
            $history = collect([[
                'action' => 'INITIAL',
                'note' => 'Input Stock Fisik Awal',
                'real_stock' => $stockOpname->real_stock,
                'difference' => $stockOpname->difference,
                'created_by' => $stockOpname->created_by,
                'creator_name' => $creator ? $creator->nm_user : 'System',
                'created_at' => $stockOpname->created_at->toDateTimeString(),
            ]]);
        }
        
        return response()->json([
            'code' => $stockOpname->code,
            'history' => $history
        ]);
    }
}
