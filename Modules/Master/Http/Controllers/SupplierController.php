<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Supplier;
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

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('master::supllier.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('master::create');
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
        return view('master::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('master::edit');
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
        $data = Supplier::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                $colors = ['warning', 'success', 'info', 'primary'];
                $color = $colors[$item->id % count($colors)];

                return '<div class="d-flex align-items-center">
                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                <a href="javascript:void(0)">
                                    <div class="symbol-label fs-3 bg-light-' . $color . ' text-' . $color . '">' . strtoupper(substr($item->name, 0, 1)) . '</div>
                                </a>
                            </div>
                            <div class="ms-5">
                                <a href="javascript:void(0)" class="text-gray-800 text-hover-primary fs-5 fw-bold">' . $item->name . '</a>
                            </div>
                        </div>';
            })
            ->addColumn('pic_name', function ($item) {
                return $item->pic_name . '<br> <div class="badge badge-light-success fw-bold">' . $item->pic_whatsapp . '</div>';
            })
            ->addColumn('address', function ($item) {
                return $item->address . '<br> <div class="badge badge-light fw-bold">' . $item->email . '</div>';
            })
            ->addColumn('action', function ($item) {
                return '
                    <div class="dropdown text-end">
                        <button class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary dropdown-toggle" 
                            type="button" 
                            id="dropdownMenuButton' . $item->id . '" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                            Actions
                            <i class="ki-outline ki-down fs-5 ms-1"></i>
                        </button>
            
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $item->id . '">
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="editProduct(' . $item->id . ')">
                                    Edit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteProduct(' . $item->id . ')">
                                    Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                ';
            })
            ->rawColumns(['name', 'action', 'pic_name', 'address'])
            ->make(true);
    }
}
