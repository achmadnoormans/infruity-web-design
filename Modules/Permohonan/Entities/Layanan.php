<?php

namespace Modules\Permohonan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Layanan extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = "m_layanan";
	protected $primaryKey = 'id_layanan';
}
