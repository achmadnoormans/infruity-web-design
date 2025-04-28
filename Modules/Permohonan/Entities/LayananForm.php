<?php

namespace Modules\Permohonan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LayananForm extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'id_layanan',
        'nama_form',
        'type',
        'id_user',
        'status',
        'deleted_at'
    ];
    protected $table = "m_layanan_form";
	protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
}
