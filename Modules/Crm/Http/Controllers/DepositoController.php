<?php

namespace Modules\Crm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Crm\Entities\Deposito;
use Modules\Crm\Entities\PointSchedule;
use Modules\Crm\Entities\Tier;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class DepositoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('crm::deposito.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('crm::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'customer_id' => 'required|exists:customer,id',
            'tier_id' => 'required|exists:crm_tier,id',
            'date' => 'required|date',
            'deposito' => 'required',
        ]);

        // Simpan data ke database
        try {
            DB::beginTransaction();

            $tier = Tier::findOrFail($validated['tier_id']);
            $pointSchedule = PointSchedule::first();
            // dd($tier, $pointSchedule);

            $deposito = new Deposito();
            $deposito->customer_id = $validated['customer_id'];
            $deposito->deposito_date = $validated['date'];
            $deposito->tier_id = $validated['tier_id'];
            $deposito->start_period = $pointSchedule->start_date;
            $deposito->end_period = $pointSchedule->end_date;
            $deposito->voucher = $tier->voucher;
            $deposito->voucher_qty = $tier->deposito / $tier->voucher;
            $deposito->deposito = preg_replace('/[^0-9]/', '', $validated['deposito']);
            $deposito->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Deposito gagal disimpan.' . $e->getMessage(),
                'data' => $deposito
            ], 404);
        }

        // Kirim response JSON
        return response()->json([
            'message' => 'Deposito berhasil disimpan.',
            'data' => $deposito
        ], 201);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('crm::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $deposito = Deposito::with('customer')->findOrFail($id);
        return response()->json($deposito);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customer,id',
            'date' => 'required|date',
            'deposito' => 'required',
        ]);

        // Simpan data ke database
        try {
            DB::beginTransaction();
            $deposito = Deposito::findOrFail($id);
            $deposito->customer_id = $validated['customer_id'];
            $deposito->date = $validated['date'];
            $deposito->deposito = preg_replace('/[^0-9]/', '', $validated['deposito']);
            $deposito->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Deposito gagal disimpan.' . $e->getMessage(),
                'data' => $deposito
            ], 404);
        }

        // Kirim response JSON
        return response()->json([
            'message' => 'Deposito berhasil disimpan.',
            'data' => $deposito
        ], 201);
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
            $deposito = Deposito::findOrFail($id);
            $deposito->delete();
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
        $data = Deposito::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return $row->customer->name;
            })
            ->addColumn('date', function ($row) {
                return '<span class="badge badge-light-success">' . date('d M Y', strtotime($row->deposito_date)) . '</span>';
            })
            ->addColumn('deposito', function ($row) {
                return 'Rp' . number_format($row->deposito, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                // $name = e($row->name);
                return '
                <div class="dropstart">
                    <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">
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
            ->rawColumns(['name', 'date', 'action'])
            ->make(true);
    }
}
