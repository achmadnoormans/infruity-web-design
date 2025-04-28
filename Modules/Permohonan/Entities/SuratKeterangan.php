<?php

namespace Modules\Permohonan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuratKeterangan extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Permohonan\Database\factories\SuratKeteranganFactory::new();
    }
}
