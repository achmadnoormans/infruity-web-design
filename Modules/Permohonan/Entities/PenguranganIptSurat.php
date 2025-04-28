<?php

namespace Modules\Permohonan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DB;

class PenguranganIptSurat extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'ipt_pengurangan_surat';

    public static function getAutoNumber()
    {
        $orderData = DB::table('ipt_pengurangan_surat')
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
