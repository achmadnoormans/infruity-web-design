<?php

namespace Modules\Transaction\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Branch;
use Modules\Master\Entities\UserBranch;
use Modules\Transaction\Entities\Transfer;
use Modules\Transaction\Entities\TransferDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class TransferController extends Controller
{
    use \App\Traits\HasAccessControl;

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if ($denied = $this->requireAccess('transfer.index')) {
            return $denied;
        }

        $data['branches'] = Branch::whereIn('id', UserBranch::getUserBranch())->get();
        return view('transaction::transfer.index2', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if ($denied = $this->requireAccess('transfer.create')) {
            return $denied;
        }

        $data['alpinejs'] = true;
        $data['data'] = null;
        $data['detail'] = null;
        $data['invoice_number'] = Transfer::getOrderNumber();
        return view('transaction::transfer.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if ($denied = $this->requireAccess('transfer.store')) {
            return $denied;
        }

        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if ($denied = $this->requireAccess('transfer.show')) {
            return $denied;
        }

        return view('transaction::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if ($denied = $this->requireAccess('transfer.edit')) {
            return $denied;
        }

        $data['alpinejs'] = true;
        $data['data'] = Transfer::with('branch', 'branchDestination', 'createdBy')->findOrFail($id);
        $data['detail'] = TransferDetail::with('product', 'product.unit')->where('transfer_id', $id)->get();
        $data['invoice_number'] = $data['data']->invoice_number;
        return view('transaction::transfer.create', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if ($denied = $this->requireAccess('transfer.update')) {
            return $denied;
        }

        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if ($denied = $this->requireAccess('transfer.destroy')) {
            return $denied;
        }

        try {
            DB::beginTransaction();
            $pos = Transfer::findOrFail($id);
            $pos->delete();
            TransferDetail::where('transfer_id', $id)->delete();
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

    public function saveTransaction(Request $request)
    {
        if ($denied = $this->requireAccess('transfer.save-transaction')) {
            return $denied;
        }

        // dd($request->all());
        $data = $request->validate([
            'branch_id' => 'required|exists:branch,id',
            'branch_destination_id' => 'required|exists:branch,id',
            'date' => 'required|date',
            'invoice_number' => 'nullable',
            'items' => 'required|array',
            'total' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'status' => 'nullable|in:draft,paid,debt,temp,pending',
        ]);

        try {
            $userId = Auth::id();
            DB::beginTransaction();
            $cek = Transfer::where('invoice_number', $data['invoice_number'])->first();
            if ($cek) {
                $pos = Transfer::find($cek->id);
                $posDetail = TransferDetail::where('transfer_id', $cek->id);
                TransferDetail::where('transfer_id', $cek->id)->delete();
                $pos->delete();
            }
            // Simpan ke tabel transfer (buat dulu kalau belum ada)
            $pos = new Transfer([
                'uuid' => Str::uuid(),
                'branch_id' => $data['branch_id'],
                'branch_destination_id' => $data['branch_destination_id'],
                'date' => $data['date'],
                'invoice_number' => Transfer::getOrderNumber(),
                'total' => $data['total'],
                'status' => $data['status'] ?? 'draft',
                'created_by' => $userId,
            ]);
            $pos->save();

            // Simpan item transaksi
            $transaksiId = $pos->id;
            foreach ($data['items'] as $item) {
                if (is_numeric($item['id'])) {
                    TransferDetail::insert([
                        'transfer_id' => $transaksiId,
                        'product_id' => $item['id'],
                        'price' => $item['price'],
                        'quantity' => $item['qty'],
                        'discount' => $item['discount'] ?? 0,
                        'subtotal' => $item['total_input'],
                        'created_at' => now(),
                        'created_by' => $userId,
                    ]);
                }
            }

            DB::commit();
            DB::disconnect();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'transaksi_id' => $transaksiId
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            DB::disconnect();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function get_data(Request $request)
    {
        $query = Transfer::with('createdBy')->whereIn('branch_id', UserBranch::getUserBranch());
        if ($request->has('status_filter') && $request->status_filter !== 'all') {
            $query = $query->where('status', $request->status_filter);
        }
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        if ($request->has('cabang_filter') && $request->cabang_filter !== 'all') {
            $query->where('branch_id', $request->cabang_filter);
        }
        $data = $query->orderBy('id', 'DESC');
        // dd($data);
        return DataTables::of($data)
            ->filter(function ($query) use ($request) {
                $search = trim($request->input('search.value'));

                if (!empty($search)) {
                    $query->where(function ($q) use ($search) {
                        $q->where('invoice_number', 'LIKE', "%{$search}%")
                            ->orWhereHas('createdBy', function ($sub) use ($search) {
                                $sub->where('nm_user', 'LIKE', "%{$search}%");
                            });
                    });
                }
            }, true)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                $html = '<div class="d-flex align-items-center">';
                $html .= '<div class="ms-5">';
                $html .= '<a href="' . route('sortir.edit', $item->id) . '" class="text-gray-800 text-hover-primary fs-5 fw-bold">' . $item->invoice_number . '</a>';
                $html .= '<br><span class="text-muted d-block fs-7">' . $item->branch->name . ' <i class="bi bi-arrow-right"></i> ' . $item->branchDestination->name . '</span>';
                $html .= '<span class="badge badge-light-danger">' . ucwords(strtolower($item->createdBy->nm_user)) . '</span>';
                return $html;
            })
            ->addColumn('date', function ($item) {
                $html = '<span class="text-muted d-block fs-8">' . date('d M Y H:i', strtotime($item->created_at)) . '</span>';
                if ($item->status == 'paid') {
                    $html .= '<span class="badge badge-light-success">Final</span>';
                } else if ($item->status == 'draft') {
                    $html .= '<span class="badge badge-light-danger">Draft</span>';
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
                                <a class="dropdown-item" href="' . route('transfer.edit', $item->id) . '">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </li>';
                if (!in_array($item->status, ['paid', 'debt'])) {
                    $html .= '
                            <li>
                                <a class="dropdown-item text-primary d-flex justify-content-center" href="javascript:void(0)" onclick="deleteProduct(' . $item->id . ')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </li>';
                }
                $html .= '
                        </ul>
                    </div>
                    ';
                return $html;
            })
            ->rawColumns(['name', 'action', 'date'])
            ->make(true);
    }
}
