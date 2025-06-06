<?php

namespace Modules\Master\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\ProductCategory;
use Modules\Master\Entities\ProductUnit;
use Modules\Transaction\Entities\ProductStock;
use DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'product_unit',
        'status',
        'hpp',
        'fee',
        'created_by',
    ];
    protected $table = 'products';

    protected static function newFactory()
    {
        return \Modules\Master\Database\factories\ProductFactory::new();
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }
    public function unit()
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit');
    }

    public function productStock()
    {
        return $this->belongsTo(ProductStock::class, 'product_id', 'id');
    }

    public function get_stock()
    {
        return $this->belongsTo('Modules\Transaction\Entities\ProductStock', 'id', 'id');
    }

    public function receipt()
    {
        return $this->belongsTo('Modules\Transaction\Entities\Receipt', 'product_id', 'id');
    }

    public static function generateProductName($baseName)
    {
        // Ambil semua produk yang nama depannya sama persis
        $existing = self::where('name', 'LIKE', $baseName . ' - %')
            ->pluck('name');

        // Cari nomor urut tertinggi
        $maxNumber = 0;
        foreach ($existing as $name) {
            if (preg_match('/^' . preg_quote($baseName, '/') . ' - (\d+)$/', $name, $matches)) {
                $number = intval($matches[1]);
                if ($number > $maxNumber) {
                    $maxNumber = $number;
                }
            }
        }

        // Buat nama baru dengan urutan berikutnya
        $newNumber = $maxNumber + 1;
        return $baseName . ' - ' . $newNumber;
    }
}
