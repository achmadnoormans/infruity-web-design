<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Yajra\DataTables\Facades\DataTables;
use Modules\Report\Entities\CustomerTransaction;
use Modules\Report\Entities\BranchTransaction;
use Modules\Report\Entities\CustomerProduct;
use Modules\Report\Entities\BranchProduct;
use Modules\Report\Entities\ProductBuang;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        return view('report::customer-transaction');
    }

    public function customer_transaction(Request $request)
    {
        return view('report::customer-transaction-rep');
    }

    public function branch_transaction(Request $request)
    {
        return view('report::branch-transaction-rep');
    }

    public function branch_product(Request $request)
    {
        return view('report::branch-product-rep');
    }
    public function customer_product(Request $request)
    {
        return view('report::product-customer-transaction-rep');
    }
    public function product_buang(Request $request)
    {
        return view('report::product-buang');
    }

    public function get_data_transaction(Request $request)
    {
        $data = CustomerTransaction::query();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {
                return $row->name ?? 'Pelanggan Umum';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d F y : H:i:s');
            })
            ->editColumn('total', function ($row) {
                return number_format($row->total, 0, ',', '.');
            })
            ->editColumn('gender', function ($row) {
                if ($row->gender == 'male') {
                    return '<span class="badge badge-light-primary">Laki-laki</span>';
                } else if ($row->gender == 'female') {
                    return '<span class="badge badge-light-success">Perempuan</span>';
                } else {
                    return '<span class="badge badge-light-danger">-</span>';
                }
            })
            ->editColumn('branch_name', function ($row) {
                switch ($row->branch_id) {
                    case 1:
                        return '<span class="badge badge-light-primary">' . $row->branch_name . '</span>';
                        break;
                    case 2:
                        return '<span class="badge badge-light-success">' . $row->branch_name . '</span>';
                        break;
                    case 3:
                        return '<span class="badge badge-light-warning">' . $row->branch_name . '</span>';
                        break;
                    case 4:
                        return '<span class="badge badge-light-info">' . $row->branch_name . '</span>';
                        break;
                    default:
                        return '<span class="badge badge-light-danger">Other</span>';
                }
            })
            ->editColumn('profit', function ($row) {
                return number_format($row->profit, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="dropstart">
                        <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">
                            <li>
                                <a class="dropdown-item" href="' . route('pos.show', $row->pos_id) . '">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </li>
                        </ul>
                    </div>';
            })
            ->rawColumns(['action', 'gender', 'branch_name'])
            ->make(true);
    }

    public function get_data_customer_transaction(Request $request)
    {
        $dr_tgl = date('Y-01-01');
        $sp_tgl = date('Y-12-31');
        $data = CustomerTransaction::getAllCustomerTransaction($dr_tgl, $sp_tgl);

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('total_omset', function ($row) {
                return number_format($row->total_omset, 0, ',', '.');
            })
            ->editColumn('profit', function ($row) {
                return number_format($row->profit, 0, ',', '.');
            })
            ->editColumn('prosentase_omset', function ($row) {
                return number_format($row->prosentase_omset, 0, ',', '.') . ' %';
            })
            ->editColumn('prosentase_profit', function ($row) {
                return number_format($row->prosentase_profit, 0, ',', '.') . ' %';
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="dropstart">
                        <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </li>
                        </ul>
                    </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function get_data_branch_transaction(Request $request)
    {
        $dr_tgl = $request->dr_tgl ?? date('Y-01-01');
        $sp_tgl = $request->sp_tgl ?? date('Y-12-31');
        $data = BranchTransaction::getAllBranchTransaction($dr_tgl, $sp_tgl);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('total_omset', function ($row) {
                return number_format($row->total_omset, 0, ',', '.');
            })
            ->editColumn('profit', function ($row) {
                return number_format($row->profit, 0, ',', '.');
            })
            ->editColumn('prosentase_omset', function ($row) {
                return number_format($row->prosentase_omset, 0, ',', '.') . ' %';
            })
            ->editColumn('prosentase_profit', function ($row) {
                return number_format($row->prosentase_profit, 0, ',', '.') . ' %';
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="dropstart">
                        <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </li>
                        </ul>
                    </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function get_data_branch_product(Request $request)
    {
        $dr_tgl = $request->dr_tgl ?? date('Y-01-01');
        $sp_tgl = $request->sp_tgl ?? date('Y-12-31');
        $data = BranchProduct::getAllBranchProduct($dr_tgl, $sp_tgl);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('branch', function ($row) {
                switch ($row->branch_id) {
                    case 1:
                        return '<span class="badge badge-light-primary">' . $row->branch . '</span>';
                        break;
                    case 2:
                        return '<span class="badge badge-light-success">' . $row->branch . '</span>';
                        break;
                    case 3:
                        return '<span class="badge badge-light-warning">' . $row->branch . '</span>';
                        break;
                    case 4:
                        return '<span class="badge badge-light-info">' . $row->branch . '</span>';
                        break;
                    default:
                        return '<span class="badge badge-light-danger">Belum Terdaftar</span>';
                }
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="dropstart">
                        <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </li>
                        </ul>
                    </div>';
            })
            ->rawColumns(['action', 'branch'])
            ->make(true);
    }

    public function get_data_customer_product(Request $request)
    {
        $dr_tgl = $request->dr_tgl ?? date('Y-01-01');
        $sp_tgl = $request->sp_tgl ?? date('Y-12-31');
        $data = CustomerProduct::getAllCustomerProduct($dr_tgl, $sp_tgl);
        return DataTables::of($data)
            ->editColumn('nama', function ($row) {
                return $row->nama ?? 'Pelanggan Umum';
            })
            ->editColumn('branch', function ($row) {
                switch ($row->branch_id) {
                    case 1:
                        return '<span class="badge badge-light-primary">' . $row->branch . '</span>';
                        break;
                    case 2:
                        return '<span class="badge badge-light-success">' . $row->branch . '</span>';
                        break;
                    case 3:
                        return '<span class="badge badge-light-warning">' . $row->branch . '</span>';
                        break;
                    case 4:
                        return '<span class="badge badge-light-info">' . $row->branch . '</span>';
                        break;
                    default:
                        return '<span class="badge badge-light-danger">Belum Terdaftar</span>';
                }
            })
            ->editColumn('gender', function ($row) {
                if ($row->gender == 'male') {
                    return '<span class="badge badge-light-primary">Laki-laki</span>';
                } else if ($row->gender == 'female') {
                    return '<span class="badge badge-light-success">Perempuan</span>';
                } else {
                    return '<span class="badge badge-light-danger">-</span>';
                }
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="dropstart">
                        <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </li>
                        </ul>
                    </div>';
            })
            ->rawColumns(['action', 'branch', 'gender'])
            ->make(true);
    }

    public function get_data_barang_buang(Request $request)
    {
        $dr_tgl = $request->dr_tgl ?? date('Y-01-01');
        $sp_tgl = $request->sp_tgl ?? date('Y-12-31');
        $data = ProductBuang::all();
        return DataTables::of($data)
            ->editColumn('satuan', function ($row) {
                switch ($row->unit_id) {
                    case 1:
                        return '<span class="badge badge-light-primary">' . $row->satuan . '</span>';
                        break;
                    case 2:
                        return '<span class="badge badge-light-success">' . $row->satuan . '</span>';
                        break;
                    case 3:
                        return '<span class="badge badge-light-warning">' . $row->satuan . '</span>';
                        break;
                    case 4:
                        return '<span class="badge badge-light-info">' . $row->satuan . '</span>';
                        break;
                    default:
                        return '<span class="badge badge-light-danger">' . $row->satuan . '</span>';
                }
            })
            ->editColumn('quantity', function ($row) {
                return number_format($row->quantity, 2);
            })
            ->editColumn('hpp', function ($row) {
                return number_format($row->hpp, 2);
            })
            ->editColumn('total_hpp', function ($row) {
                return number_format($row->total_hpp, 2);
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="dropstart">
                        <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </li>
                        </ul>
                    </div>';
            })
            ->rawColumns(['action', 'satuan'])
            ->make(true);
    }
}
