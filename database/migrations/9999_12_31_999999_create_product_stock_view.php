<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_wholesale");
        DB::statement("
            CREATE VIEW view_wholesale AS
            SELECT 
                wholesale.id AS id,
                wholesale.order_number AS order_number,
                wholesale.status AS status,
                wholesale.order_date,
                COUNT(wholesale_product.id) AS total_product
            FROM wholesale
            JOIN wholesale_product ON wholesale_product.wholesale_id = wholesale.id
            GROUP BY 
                wholesale.id, 
                wholesale.status,
                wholesale.order_date
        ");

        DB::statement("DROP VIEW IF EXISTS transaction_stock");
        DB::statement("
            CREATE VIEW transaction_stock AS
            SELECT * FROM (
                SELECT
                    product_id,
                    quantity,
                    avg_price,
                    date,
                    `code` AS reff
                FROM
                    stock_in UNION
                SELECT
                    product_id,
                    - quantity,
                    avg_price,
                    date,
                    `code` 
                FROM
                    stock_out UNION
                SELECT
                    product_id,
                    quantity,
                    price,
                    wholesale.order_date,
                    'wholelsale' AS reff 
                FROM
                    wholesale_product
                    JOIN wholesale ON wholesale_product.wholesale_id = wholesale.id 
                WHERE
                    wholesale_product.`status` = 'complete' 
                    AND product_id != 0 UNION
                SELECT
                    product_id,
                    - quantity,
                    avg_price,
                    date,
                    'stock-out'
                FROM
                    stock_out_transaction
            ) AS Q
        ");

        DB::statement("DROP VIEW IF EXISTS sortir_view");
        DB::statement("
            CREATE VIEW sortir_view AS
            SELECT
                A.*,
                B.product_id,
                SUM( B.quantity ) AS stock_available,
                AVG( B.avg_price ) AS hpp,
                C.abbreviation AS satuan
            FROM
                products AS A
                LEFT JOIN transaction_stock AS B ON A.id = B.product_id
                LEFT JOIN product_units AS C ON A.product_unit = C.id
            GROUP BY
                A.id, B.product_id, C.abbreviation
            ORDER BY 
                stock_available DESC
        ");

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
                LEFT JOIN transaction_stock AS B ON A.id = B.product_id
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
        DB::statement("DROP VIEW IF EXISTS transaction_stock");
        DB::statement("DROP VIEW IF EXISTS sortir_view");
        DB::statement("DROP VIEW IF EXISTS view_wholesale");
    }
};
