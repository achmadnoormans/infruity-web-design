<?php

namespace Modules\Permohonan\Entities;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermohonanSurat extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = "t_permohonan_surat";
    protected $primaryKey = 'id';

    public function permohonan()
    {
        return $this->belongsTo('Modules\Permohonan\Entities\Permohonan', 'id', 'id_surat');
    }

    public static function getAutoNumber()
    {
        $orderData = DB::table('t_permohonan_surat')
            ->select(DB::raw('CAST( RIGHT ( nomer_surat, 5 ) AS UNSIGNED ) + 1 AS order_number'))
            ->orderByRaw('CAST(RIGHT ( nomer_surat, 5 ) AS UNSIGNED) DESC')
            ->limit(1)
            ->get()->first();
        $orderPad = '00001';
        if (isset($orderData) && $orderData->order_number != null) {
            $orderPad = str_pad($orderData->order_number, 5, '0', STR_PAD_LEFT);
        }
        return $orderPad;
    }
}
