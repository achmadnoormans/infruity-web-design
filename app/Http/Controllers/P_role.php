<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Response;
use App\Models\Role;
use DB;
use Validator;
use App\Http\Requests\RoleRequest;
use Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\RoleMenu;
use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class P_role extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["data"] = Role::get();

        return view("admin.role.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("role");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        try {

            DB::beginTransaction();

            $role = new Role();
            $role->nm_role = $request->nm_role;
            $role->id_creator = Auth::user()->id_user;

            if (strtolower($role->nm_role) == "administrator") {
                return redirect()->back()->with('error', 'Akses Terbatas');
            }

            $role->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data role Gagal Disimpan' . $e->getMessage());
        }

        return redirect("role")->with('success', 'Data role Disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::with('roleMenu')->findOrFail($id);

        // ambil semua nama route
        $routes = collect(Route::getRoutes())
            ->map(fn($route) => $route->getName())
            ->filter() // hanya route yang punya name
            ->reject(fn($name) => Str::contains($name, [
                'data', 'ajax', 'debug', 'livewire', 'ignition', 'csrf-cookie', 'dashboard', 'landing',
                'change-password', 'save_change_password',
                'login', 'logout', 'register', 'forgot-password', 'reset-password',
                ]))
            ->values();

        // group by prefix
        $groupedRoutes = $routes->groupBy(fn($name) => Str::before($name, '.'));

        // ambil permission role yang aktif
        $rolePermissions = $role->roleMenu->pluck('permission')->toArray();

        return view('admin.role.edit', compact('role', 'groupedRoutes', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data["data"] = Role::findOrFail($id);
        return view("role", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        try {

            // save to user
            DB::beginTransaction();

            $role = Role::findOrFail($id);
            $role->nm_role = $request->nm_role;
            $role->id_creator = Auth::user()->id_user;

            if (strtolower($role->nm_role) == "administrator") {
                return redirect()->back()->with('error', 'Akses Terbatas');
            }
            $role->save();
            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data role Gagal Disimpan');
        }

        return redirect("role")->with('success', 'Data role Disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $role = Role::findOrFail($id);
            if (strtolower($role->nm_role) == "administrator") {
                return redirect()->back()->with('error', 'Akses Terbatas');
            }
            $role->delete();

        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }

        return redirect("role")->with('success', 'Data role dihapus');
    }


    public function get_data(Request $request)
    {
        if (class_exists(\Debugbar::class)) {
            \Debugbar::disable();
        }

        $data = Role::query();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
                    <div class="dropstart">
                        <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">
                            <li>
                                <a class="dropdown-item" href="' . route('roles.show', $row->id_role) . '">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="editProduct(' . $row->id_role . ')">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-primary d-flex justify-content-center" href="javascript:void(0)" onclick="deleteProduct(' . $row->id_role . ')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </li>
                        </ul>
                    </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
