<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Permission;
use App\Models\RoleMenu;

class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'id_role';
    // public $incrementing = false; // karena bukan auto increment
    protected $fillable = ['nm_role', 'description', 'id_creator'];

    public function roleMenu()
    {
        return $this->hasMany(RoleMenu::class, 'id_role', 'id_role');
    }

    // shortcut: ambil permissions (string) dari role_menu
    public function permissions()
    {
        return $this->roleMenu->pluck('permission');
    }
}
