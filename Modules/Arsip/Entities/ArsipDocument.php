<?php

namespace Modules\Arsip\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArsipDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'deleted_at'
    ];
    protected $table = "arsip_document";
    protected $primaryKey = 'id';
    public function arsip()
    {
        return $this->belongsTo(Arsip::class, 'arsip_id');
    }
}
