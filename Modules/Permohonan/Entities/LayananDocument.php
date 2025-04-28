<?php

namespace Modules\Permohonan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LayananDocument extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = "m_layanan_doc";
	protected $primaryKey = 'id';
    
}
