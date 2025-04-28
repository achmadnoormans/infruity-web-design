<?php

namespace Modules\Permohonan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermohonanHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'deleted_at'
    ];
    protected $table = "t_permohonan_history";
    protected $primaryKey = 'id';

    public function permohonan()
    {
        return $this->belongsTo('Modules\Permohonan\Entities\Permohonan', 'id_permohonan', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_verifikator', 'id_user');
    }

    public static function getRoleUser($id_permohonan, $type = NULL)
    {
       return self::leftJoin('role_user', 't_permohonan_history.id_verifikator', '=', 'role_user.id_user')
            ->leftJoin('role', 'role_user.id_role', '=', 'role.id_role')
            ->where('t_permohonan_history.id_permohonan', $id_permohonan)
            ->where('t_permohonan_history.type', $type)
            ->get();
    }

}
