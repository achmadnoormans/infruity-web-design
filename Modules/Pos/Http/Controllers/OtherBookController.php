<?php

namespace Modules\Pos\Http\Controllers;

use DB;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pos\Entities\PosModel;
use Yajra\DataTables\Facades\DataTables;

class OtherBookController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('pos::otherbook.index');
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
            $pos->process_status = 'done';
            $pos->process_date = date('Y-m-d H:i:s');
            $pos->save();
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    public function get_data(Request $request)
    {
        $query = PosModel::with('customer', 'payment')->where('process_status', '!=', 'none');
        if ($request->has('status_filter') && $request->status_filter !== 'all') {
            $query = $query->where('process_status', $request->status_filter);
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
                    $html .= '<a href="' . url('pos') . '/' . $item->id . '/show' . '" class="text-gray-800 text-hover-primary fs-5 fw-bold">' . $item->customer->name . '</a>';
                    $html .= '<br><span class="text-muted d-block fs-7">' . ($item->customer->address) . '</span>';
                } else {
                    $html .= '<a href="' . url('pos') . '/' . $item->id . '/show' . '" class="text-gray-800 text-hover-primary fs-5 fw-bold">Pelanggan Umum</a>';
                }
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('ongkir', function ($item) {
                return 'Rp. ' . number_format($item->ongkir, 0, ',', '.');
            })
            ->editColumn('process_status', function ($item) {
                $html = '';
                if ($item->process_status == 'pending') {
                    $html .= '<span class="badge badge-light-danger">Proses</span>';
                } else if ($item->process_status == 'done') {
                    $html .= '<span class="badge badge-light-success">Selesai</span>';
                } else {
                    $html .= '<span class="badge badge-light-warning">' . $item->process_status . '</span>';
                }
                return $html;
            })
            ->addColumn('date', function ($item) {
                $html = '<span class="text-muted d-block fs-8">' . date('d M Y', strtotime($item->process_date)) . '</span>';
                $html .= '<span class="text-muted d-block fs-8">' . date('H:i', strtotime($item->process_date)) . '</span>';
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
                if (check_access('pos.show')) {
                    $html .= '
                            <li>
                                <a class="dropdown-item" href="' . route('pos.show', $item->id) . '">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </li>';
                }
                $html .= '
                            <li>
                                <a class="dropdown-item" href="' . route('pos.payment', $item->id) . '">
                                    <i class="bi bi-cash-stack"></i>
                                </a>
                            </li>';
                if (check_access('pos.set-selesai')) {
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
            ->rawColumns(['name', 'action', 'date', 'process_status'])
            ->make(true);
    }
}
