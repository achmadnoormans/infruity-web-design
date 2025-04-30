<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Location;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;

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
            'description' => 'string|max:255',
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
            ->addColumn('action', function ($location) {
                return '
                    <div class="dropdown text-end">
                        <button class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary dropdown-toggle" 
                            type="button" 
                            id="dropdownMenuButton' . $location->id . '" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                            Actions
                            <i class="ki-outline ki-down fs-5 ms-1"></i>
                        </button>
            
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $location->id . '">
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="editProduct(' . $location->id . ')">
                                    Edit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteProduct(' . $location->id . ')">
                                    Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                ';
            })
            ->rawColumns(['name', 'action'])
            ->make(true);
    }
}
