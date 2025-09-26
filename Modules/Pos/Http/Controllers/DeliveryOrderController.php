<?php

namespace Modules\Pos\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pos\Entities\PosModel;
use Modules\Master\Entities\Staff;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DeliveryOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('pos::delivery-order.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('pos::create');
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
        return view('pos::show');
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
        //
    }

    public function setSelesai($id)
    {
        try {
            DB::beginTransaction();
            $pos = PosModel::find($id);
            $pos->ongkir_status = 'delivered';
            $pos->save();
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    public function getCourier(Request $request)
    {
        $staffs = Staff::all();
        return view('pos::delivery-order.list-kurir', compact('staffs'));
    }

    public function updateCourier(Request $request)
    {
        $kurirIds = $request->input('kurir_ids', []);
        $staffs = Staff::all();
        foreach ($staffs as $staff) {
            $staff->is_kurir = in_array($staff->id, $kurirIds);
            $staff->save();
        }
        return response()->json(['success' => true]);
    }

    public function get_data(Request $request)
    {
        $query = PosModel::with('customer', 'payment')->where('ongkir', '>', 0);
        if ($request->has('status_filter') && $request->status_filter !== 'all') {
            $query = $query->where('ongkir_status', $request->status_filter);
        }
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        $data = $query->get();
        // dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                $html = '<div class="d-flex align-items-center">';
                $html .= '<div class="ms-5">';
                if (isset($item->customer->name)) {
                    $html .= '<a href="' . url('pos') . '/' . $item->id . '/show' . '" class="text-gray-800 text-hover-primary fs-5 fw-bold">' . $item->customer->name ?? '-' . '</a>';
                    $html .= '<br><span class="text-muted d-block fs-7">' . ($item->ongkir_address) . '</span>';
                } else {
                    $html .= '<a href="' . url('pos') . '/' . $item->id . '/show' . '" class="text-gray-800 text-hover-primary fs-5 fw-bold">Pelanggan Umum</a>';
                    $html .= '<br><span class="text-muted d-block fs-7">' . ($item->ongkir_address) . '</span>';
                }
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->addColumn('courier', function ($item) {
                $courier = $item->courier->name ?? '-';
                $html = '';
                $html .= '<span class="text-muted d-block fs-5">' . ($courier) . '</span>';
                $html .= '<span class="text-muted d-block fs-8">Rp. ' . number_format($item->ongkir, 0, ',', '.') . '</span>';
                return $html;
            })
            ->editColumn('ongkir', function ($item) {
                $html = '';
                $html .= '<span class="text-muted d-block fs-8">Rp. ' . number_format($item->ongkir, 0, ',', '.') . '</span>';
                if ($item->ongkri_status == 'delivered' && $item->payment_status == 'paid') {
                    $html .= '<span class="badge badge-light-success">Selesai</span>';
                } else {
                    $html .= '<span class="badge badge-light-danger">Process</span>';
                }
                return $html;
            })
            ->addColumn('total_price', function ($item) {
                return 'Rp. ' . number_format($item->total_price, 0, ',', '.');
            })
            ->addColumn('total_quantity', function ($item) {
                return $item->total_quantity;
            })
            ->addColumn('date', function ($item) {
                $html = '<span class="text-muted d-block fs-8">' . date('d M Y', strtotime($item->ongkir_date)) . '</span>';
                $html .= '<span class="text-muted d-block fs-8">' . date('H:i', strtotime($item->ongkir_time)) . '</span>';
                return $html;
            })
            ->addColumn('status', function ($item) {
                $html = '';
                if ($item->ongkir_status == 'delivered') {
                    $html .= '<span class="badge badge-light-success">Diterima</span>';
                } else if ($item->ongkir_status == 'draft') {
                    $html .= '<span class="badge badge-light-danger">Belum Dikirm</span>';
                } else {
                    $html .= '<span class="badge badge-light-warning">' . $item->ongkir_status . '</span>';
                }
                $html .= '<div class="d-flex"></div>';
                if ($item->status == 'paid') {
                    $html .= '<span class="badge badge-light-success">Lunas</span>';
                } else if ($item->status == 'draft') {
                    $html .= '<span class="badge badge-light-danger">Belum Lunas</span>';
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
                        <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">';
                $html .= '                        
                            <li>
                                <a class="dropdown-item" href="' . route('pos.show', $item->id) . '">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </li>';
                if (!in_array($item->status, ['paid'])) {
                    $html .= '                        
                            <li>
                                <a class="dropdown-item" href="' . route('pos.payment', $item->id) . '">
                                    <i class="bi bi-cash-stack"></i>
                                </a>
                            </li>';
                }
                if (!in_array($item->status_ongkir, ['delivered'])) {
                    $html .= '
                            <li>
                            <a class="dropdown-item text-primary d-flex justify-content-center" href="javascript:void(0)" onclick="setSelesai(' . $item->id . ')">
                                <i class="bi bi-check2-circle"></i>
                            </a>
                        </li>';
                }
                $html .= '                        
                        </ul>
                    </div>
                    ';
                return $html;
            })
            ->rawColumns(['name', 'action', 'date', 'ongkir', 'courier', 'status'])
            ->make(true);
    }
}
