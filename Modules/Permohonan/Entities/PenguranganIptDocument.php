<?php

namespace Modules\Permohonan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenguranganIptDocument extends Model
{
    protected $fillable = [
        'deleted_at'
    ];
    protected $table = "ipt_pengurangan_document";
	protected $primaryKey = 'id';
}
