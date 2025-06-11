<?php

namespace Modules\Pos\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pos\Entities\PosDetailModel;
use Modules\Pos\Entities\PosModel;
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
        return view('pos::pos.create', $data);
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

        DB::beginTransaction();
        try {

            $userId = Auth::id(); // Ambil user sekali
            $pos = new PosModel([
                'customer_id' => $request->customer_id,
                'date' => date('Y-m-d'),
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

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
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

    public function get_data(Request $request)
    {
        $data = PosModel::with('customer')->get();
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
                                <a href="' . url('pos') . '/' . $item->id . '/show' . '" class="text-gray-800 text-hover-primary fs-5 fw-bold">#' . $item->customer->name . '</a>
                                <br>
                                <span class="text-muted d-block"> Tgl : ' . dateindo($item->date) . '</span>
                                <span class="text-muted d-block"> Jml Prod : ' . ($item->total_quantity) . '</span>
                            </div>';
                } else {
                    $html .= '<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                <a href="' . url('pos') . '/' . $item->id . '/show' . '">
                                    <div class="symbol-label fs-3 bg-light-' . $color . ' text-' . $color . '">' . strtoupper(substr('#', 0, 1)) . '</div>
                                </a>
                            </div>
                            <div class="ms-5">
                                <a href="' . url('pos') . '/' . $item->id . '/show' . '" class="text-gray-800 text-hover-primary fs-5 fw-bold">Tidak Ada Customer</a>
                                <br>
                                <span class="text-muted d-block"> Tgl : ' . dateindo($item->date) . '</span>
                                <span class="text-muted d-block"> Jml Prod : ' . ($item->total_quantity) . '</span>
                            </div>';
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
                return dateindo($item->date);
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
                                <a class="dropdown-item" href="' . route('pos.edit', $item->id) . '">
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
            ->rawColumns(['name', 'action'])
            ->make(true);
    }
}
