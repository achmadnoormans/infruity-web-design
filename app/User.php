<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Master\Entities\UserBranch;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    // public $incrementing = false;
    // public $keyType = 'string';

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
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
        return $this->belongsTo('App\Models\RoleUser', 'id_user', 'id_user');
    }

    public function branches()
    {
        return $this->hasMany(UserBranch::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'id_user', 'id_user');
    }

    public static function generatedId($id_perush)
    {
        $id = self::where("id_perush", $id_perush)->orderBy("last_id", "desc")->get()->first();

        $last = 0;
        if ($id != null) {
            $last = (int)$id->last_id + 1;
        }

        return $last;
    }
}
