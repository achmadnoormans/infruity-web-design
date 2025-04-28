<?php

namespace Modules\Permohonan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatusDokumen extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = "m_status";
	protected $primaryKey = 'id_status';
}
