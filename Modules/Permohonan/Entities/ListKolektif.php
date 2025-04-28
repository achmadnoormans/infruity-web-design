<?php

namespace Modules\Permohonan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ListKolektif extends Model
{
    use HasFactory;
    protected $fillable = [];
    protected $table = "t_permohonan_kolektif_list";
	protected $primaryKey = 'id';

    public function permohonan()
    {
        return $this->belongsTo('Modules\Permohonan\Entities\Permohonan', 'id_permohonan', 'id');
    }
}
