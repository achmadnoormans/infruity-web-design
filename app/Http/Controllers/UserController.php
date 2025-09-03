<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\RoleUser;
use App\User;
use Auth;
use DB;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data['role'] = Role::all();
        return view("admin.user.index", $data);
    }

    public function create()
    {

    }

    public function edit($id)
    {
        if (class_exists(\Debugbar::class)) {
            \Debugbar::disable();
        }
        $user = User::with('RoleUser.role')->findOrFail($id);
        return response()->json($user);
    }

    public function store(UserRequest $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $user = new User();
            $user->username = $request->email;
            $user->email = $request->email;
            $user->nm_user = $request->full_name;
            $user->password = Hash::make($request->password);
            $user->save();
            // add roles
            $role = new RoleUser();
            $role->id_user = DB::getPdo()->lastInsertId();
            $role->id_role = $request->id_role ?? 99;
            $role->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data User Gagal Disimpan' . $e->getMessage())->withInput($request->all());
        }

        return redirect('user')->with('success', 'Data User Disimpan');
    }

    public function show($id)
    {
        abort(404);
    }

    public function update(UserRequest $request, $id)
    {
        try {
            // save to user
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->username = $request->email;
            $user->email = $request->email;
            $user->nm_user = $request->full_name;
            $user->password = Hash::make($request->password);
            $user->save();
            // add roles
            RoleUser::where('id_user', $id)->delete();
            $role = new RoleUser();
            $role->id_user = $id;
            $role->id_role = $request->id_role ?? 99;
            $role->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data User Gagal Disimpan');
        }

        return redirect('user')->with('success', 'Data User Disimpan');
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            // $user = User::findOrFail($id);
            // $user->delete();
            RoleUser::where('id_user', $id)->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.'
            ]);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Data masih digunakan di table lain'
            ]);
        }
    }

    public function logout($id = null)
    {
        Auth::logout();

        if (isset($id) && $id != null) {
            return redirect('auth/login')->with('error', $id);
        } else {
            return redirect('auth/login')->with('error', 'Anda keluar Aplikasi');
        }
    }

    public function get_data(Request $request)
    {
        if (class_exists(\Debugbar::class)) {
            \Debugbar::disable();
        }

        $data = User::with('RoleUser.role')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('role', function ($row) {
                return $row->RoleUser->map(function ($ru) {
                    return $ru->role->nm_role ?? '-';
                })->implode(', ');
            })
            ->addColumn('nm_user', function ($row) {
                return $row->nm_user . '<br>' . $row->username;
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="dropstart">
                        <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="editProduct(' . $row->id_user . ')">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-primary d-flex justify-content-center" href="javascript:void(0)" onclick="deleteProduct(' . $row->id_user . ')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </li>
                        </ul>
                    </div>';
            })
            ->rawColumns(['nm_user', 'action'])
            ->make(true);
    }
}
