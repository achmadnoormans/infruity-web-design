<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Session;

class RoleUser extends Model
{
    protected $table = "role_user";
    protected $primaryKey = 'id_ru';
    
    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'id_role', 'id_role');
    }
}
