<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Location;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Auth;
use Exception;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('master::location.index');
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
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        // Simpan data ke database
        try {
            DB::beginTransaction();
            $location = new Location();
            $location->name = $validated['name'];
            $location->address = $validated['address'] ?? null;
            $location->description = $validated['description'] ?? null;
            $location->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Stok Lokasi gagal disimpan.',
                'data' => $location
            ], 404);
        }

        // Kirim response JSON
        return response()->json([
            'message' => 'Kategori berhasil disimpan.',
            'data' => $location
        ], 201);
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
        $location = Location::findOrFail($id);
        return response()->json($location);
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
            'name' => 'required|string|max:255',
            'address' => 'string|max:255',
            'description' => 'string|max:255',
        ]);

        try {
            DB::beginTransaction();
            $location = Location::findOrFail($id);
            $location->name = $validated['name'];
            $location->address = $validated['address'] ?? null;
            $location->description = $validated['description'] ?? null;
            $location->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Stock Location updated failed']);
        }

        return response()->json(['message' => 'Stock Location updated successfully']);
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
            $location = Location::findOrFail($id);
            $location->delete();
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
        $data = Location::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                $colors = ['warning', 'success', 'info', 'primary'];
                $color = $colors[$item->id % count($colors)];
                $textName = '<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                <a href="javascript:void(0)">
                                    <div class="symbol-label fs-3 bg-light-' . $color . ' text-' . $color . '">' . strtoupper(substr($item->name, 0, 1)) . '</div>
                                </a>
                            </div>';

                return '<div class="d-flex align-items-center">
                            <div class="ms-5">
                                <a href="javascript:void(0)" class="text-gray-800 text-hover-primary fs-5 fw-bold">' . $item->name . '</a>
                            </div>
                        </div>';
            })
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
            ->rawColumns(['name', 'action'])
            ->make(true);
    }
}
