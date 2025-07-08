<?php

namespace Modules\Crm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Crm\Entities\Tier;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class TierController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('crm::tier.index');
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
            'name' => 'required|string|max:255|unique:crm_tier,name',
            'level' => 'required|integer|unique:crm_tier,level',
            'exp' => 'required|integer',
        ]);

        // Simpan data ke database
        try {
            DB::beginTransaction();
            $tier = new Tier();
            $tier->name = $validated['name'];
            $tier->level = $validated['level'];
            $tier->exp = $validated['exp'];
            $tier->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Tier gagal disimpan.',
                'data' => $tier
            ], 404);
        }

        // Kirim response JSON
        return response()->json([
            'message' => 'Tier berhasil disimpan.',
            'data' => $tier
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
        $tier = Tier::findOrFail($id);
        return response()->json($tier);
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
            'name' => 'required|string|max:255|unique:crm_tier,name,' . $id,
            'level' => 'required|integer|unique:crm_tier,level,' . $id,
            'exp' => 'required',
        ]);

        // Simpan data ke database
        try {
            DB::beginTransaction();
            $tier = Tier::findOrFail($id);
            $tier->name = $validated['name'];
            $tier->level = $validated['level'];
            $tier->exp = preg_replace('/[^0-9]/', '', $validated['exp']);
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('tiers', 'public');
                $tier->icon = $path;
            }
            $tier->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Tier gagal disimpan.' . $e->getMessage(),
                'data' => $tier
            ], 404);
        }

        // Kirim response JSON
        return response()->json([
            'message' => 'Tier berhasil disimpan.',
            'data' => $tier
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
            $tier = Tier::findOrFail($id);
            $tier->delete();
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
        $data = Tier::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $name = e($row->name);
                return '
                <div class="dropstart">
                    <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi ' . $name . '">
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
            ->addColumn('exp', function ($row) {
                return toNumber($row->exp);
            })
            ->rawColumns(['name', 'exp', 'action'])
            ->make(true);
    }
}
