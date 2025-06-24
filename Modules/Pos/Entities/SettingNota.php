<?php

namespace Modules\Pos\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SettingNota extends Model
{
    use HasFactory;

    protected $fillable = [
        'header',
        'footer',
        'craeated_by',
        'updated_by',
    ];
    protected $table = 'setting_nota';
    
    protected static function newFactory()
    {
        return \Modules\Pos\Database\factories\SettingNotaFactory::new();
    }
}
