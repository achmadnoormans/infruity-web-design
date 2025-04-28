<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
Use Response;
use App\Models\RoleMenu;
use DB;
use Validator;
use App\Http\Requests\RoleMenuRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\Perusahaan;
use Auth;
use Session;
use App\Models\Module;

class P_rolemenu extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $data["data"] = RoleMenu::with('role')->paginate(10);
        return view("role-menu", $data);
    }

    public function create()
    {

    }

    public function store(UserRequest $request)
    {
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
            $role = RoleUser::findOrFail($request->id_ru);
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

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }

        return redirect()->back()->with('success', 'Data User Dihapus');
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
}
