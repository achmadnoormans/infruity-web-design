<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Exception;
Use Response;
use App\Models\Role;
use DB;
use Validator;
use App\Http\Requests\RoleRequest;
use Auth;

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
        
        return view("role", $data);
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

            $role                       = new Role();
            $role->nm_role = $request->nm_role;
            $role->id_creator = Auth::user()->id_user;

            if(strtolower($role->nm_role)=="administrator"){
                return redirect()->back()->with('error', 'Akses Terbatas');
            }

            $role->save();

            DB::commit();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data role Gagal Disimpan'.$e->getMessage());
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
        //
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

            $role                       = Role::findOrFail($id);
            $role->nm_role              = $request->nm_role;
            $role->id_creator           = Auth::user()->id_user;
            
            if(strtolower($role->nm_role)=="administrator"){
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
        try{
            
            $role                       = Role::findOrFail($id);
            if(strtolower($role->nm_role)=="administrator"){
                return redirect()->back()->with('error', 'Akses Terbatas');
            }
            $role->delete();
            
        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Data masih digunakan di table lain');
        }
        
        return redirect("role")->with('success', 'Data role dihapus');
    }
}
