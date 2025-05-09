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
        DB::statement("DROP VIEW IF EXISTS product_stock");
        
        DB::statement("
            CREATE VIEW product_stock AS
            SELECT
                A.*,
                    C.abbreviation AS unit,
                COALESCE(SUM(B.quantity), 0) AS stock_available,
                AVG(B.avg_price) AS hpp,
                CASE
                    WHEN COALESCE(SUM(B.quantity), 0) = 0 THEN 'danger'
                    WHEN COALESCE(SUM(B.quantity), 0) <= A.limit THEN 'warning'
                    ELSE 'success'
                END AS stock_status
            FROM
                products AS A
                LEFT JOIN stock_in AS B ON A.id = B.product_id
                    LEFT JOIN product_units AS C ON C.id = A.product_unit
            GROUP BY
                A.id, C.abbreviation
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS product_stock");
    }
};
