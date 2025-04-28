<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Redirect;
use Session;
use Illuminate\Support\Facades\Validator;
use Modules\Permohonan\Entities\Permohonan;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            'captcha' => 'required|captcha',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        if (Auth::attempt(array(
            'username' => $request->email,
            'password' => $request->password,
        ))) {
            $role_user = RoleUser::where("id_user", Auth::user()->id_user)->OrderBy("created_at", "asc")->first();
            $notif = "User tidak terdaftar di Role Apapun";
            if ($role_user == null) {
                return redirect('auth/logout/' . $notif);
            }
            $role["role"] = Role::find($role_user->id_role)->toArray();
            Session($role);
            if (Session('role')['id_role'] == 99) {
                $permohonan = Permohonan::where('id_user', Auth::user()->id_user)->get();
                if (isset($permohonan) && count($permohonan) > 0) {
                    return redirect('list-permohonan')->with('success', 'Selamat Datang ' . Auth::user()->nm_user);
                } else {
                    return redirect('dashboard')->with('success', 'Selamat Datang ' . Auth::user()->nm_user);
                }
            } else {
                return redirect('dashboard')->with('success', 'Selamat Datang ' . Auth::user()->nm_user);
            }
        } else {
            return redirect()->back()->with('error', 'Username atau Password tidak terdaftar');
        }
    }
}
