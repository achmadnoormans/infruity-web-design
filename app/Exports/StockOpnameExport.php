<?php

namespace App\Exports;

use Modules\Transaction\Entities\StockOpname;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Modules\Master\Entities\Branch;
use Modules\Master\Entities\UserBranch;
use Carbon\Carbon;

class StockOpnameExport implements FromView, ShouldAutoSize
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $userBranches = UserBranch::getUserBranch();
        $query = StockOpname::with('product.unit')
            ->whereIn('stock_opname.branch_id', $userBranches);

        $branchStr = 'SEMUA CABANG';
        if ($this->request->has('cabang_filter') && $this->request->cabang_filter !== 'all') {
            $query->where('stock_opname.branch_id', $this->request->cabang_filter);
            $branch = Branch::find($this->request->cabang_filter);
            if ($branch) {
                $branchStr = $branch->name;
            }
        }

        $dateStr = '';
        if ($this->request->has('start_date') && $this->request->start_date != '') {
            $query->whereDate('stock_opname.date', '>=', $this->request->start_date);
            $start = Carbon::parse($this->request->start_date);
            $dateStr .= $start->translatedFormat('d F Y');
        }

        if ($this->request->has('end_date') && $this->request->end_date != '') {
            $query->whereDate('stock_opname.date', '<=', $this->request->end_date);
            $end = Carbon::parse($this->request->end_date);
            if ($this->request->start_date != $this->request->end_date) {
                $dateStr .= ' - ' . $end->translatedFormat('d F Y');
            }
        }

        if ($dateStr == '') {
            $dateStr = 'SEMUA TANGGAL';
        }

        $data = $query->orderBy('stock_opname.created_at', 'desc')->get();

        return view('transaction::stock-opname.exports.excel', [
            'data' => $data,
            'branchStr' => $branchStr,
            'dateStr' => $dateStr
        ]);
    }
}
