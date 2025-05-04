<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DB;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Master\Database\factories\RegionFactory::new();
    }

    public static function getProvince($provinceId)
    {
        return DB::table('reg_provinces')->where('id', $provinceId)->first();
    }

    public static function getCity($cityId)
    {
        return DB::table('reg_regencies')->where('id', $cityId)->first();
    }

    public static function getDistrict($districtId)
    {
        return DB::table('reg_districts')->where('id', $districtId)->first();
    }

    public static function getVillage($villageId)
    {
        return DB::table('reg_villages')->where('id', $villageId)->first();
    }
}
