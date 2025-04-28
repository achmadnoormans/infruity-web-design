<?php

namespace Modules\Permohonan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermohonanDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'deleted_at'
    ];
    protected $table = "t_permohonan_document";
	protected $primaryKey = 'id';

}
