<?php

namespace Modules\Permohonan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermohonanArsip extends Model
{
    use HasFactory;
    protected $fillable = [
        'deleted_at'
    ];
    protected $table = "t_permohonan_arsip";
	protected $primaryKey = 'id';

    public function permohonan()
	{
		return $this->belongsTo('Modules\Permohonan\Entities\Permohonan', 'id_permohonan', 'id');
	}
}
