<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("DROP VIEW IF EXISTS sortir_view");
        
        DB::statement("
            CREATE VIEW sortir_view AS
            SELECT
                B.id,
                B.`name`,
                B.sku,
                B.image,
                C.abbreviation,
                A.product_id,
                D.id AS wholesale_id,
                D.order_number,
                SUM( A.quantity ) AS stock_available 
            FROM
                `wholesale_product` AS A
                JOIN products AS B ON A.product_id = B.id 
                JOIN product_units C ON B.product_unit = C.id
                JOIN wholesale AS D ON A.wholesale_id = D.id
            WHERE
                A.hpp IS NOT NULL 
            GROUP BY
                product_id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS sortir_view");
    }
};
