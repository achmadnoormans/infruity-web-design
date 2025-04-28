<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    // public $incrementing = false;
	// public $keyType = 'string';

    protected $fillable = [
        'name', 'email', 'username','password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function RoleMenu()
    {
        return $this->hasMany('App\Models\RoleMenu', 'id_user', 'id_user');
    }
    
    public function RoleUser()
    {
        return $this->hasMany('App\Models\RoleUser', 'id_user', 'id_user');
    }

    public function Perusahaan()
    {
        return $this->belongsTo('App\Models\Perusahaan', 'id_perush', 'id_perush');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'id_user', 'id_user');
    }

    public function karyawan()
    {
        return $this->belongsTo('App\Models\Karyawan', 'id_karyawan', 'id_karyawan');
    }

    public function pelanggan()
    {
        return $this->belongsTo('App\Models\Pelanggan', 'id_pelanggan', 'id_pelanggan');
    }

    public function sopir()
    {
        return $this->belongsTo('Modules\Operasional\Entities\Sopir', 'id_sopir', 'id_sopir');
    }

    public static function generatedId($id_perush)
    {
        $id = self::where("id_perush", $id_perush)->orderBy("last_id", "desc")->get()->first();
        
        $last = 0;
        if($id!=null){
            $last = (Int)$id->last_id+1;
        }

        return $last;
    }
}
