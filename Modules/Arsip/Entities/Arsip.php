<?php

namespace Modules\Arsip\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Arsip extends Model
{
    use HasFactory;
    protected $fillable = [
        'deleted_at'
    ];
    protected $table = "arsip";
    protected $primaryKey = 'id';

    public function arsipDocument()
    {
        return $this->hasOne(ArsipDocument::class, 'arsip_id');
    }
}
