<?php

namespace App\Http\Middleware;

use Closure;

class ChekUser
{
    public function handle($request, Closure $next)
    {
        $role = Session("role")["id_role"];
        $routeName = $request->route()->getName();
        $data = \App\Models\RoleMenu::checkAccess($routeName);
        // dd($role, $routeName, $data);
        if ($data) {
            return $next($request);
        } else {
            // abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ' . $routeName . '. Hubungi Admin untuk Aktivasi Kembali');
        }
    }
}
