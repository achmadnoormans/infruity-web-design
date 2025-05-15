<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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

        DB::statement("DROP VIEW IF EXISTS sortir_view");
        DB::statement("
            CREATE VIEW sortir_view AS
            SELECT
                B.id,
                B.`name`,
                A.product_id,
                C.abbreviation AS satuan,
                COUNT(D.id) AS wholesale_id,
                GROUP_CONCAT(D.order_number) AS order_number,
                SUM( A.quantity ) AS stock_available 
            FROM
                `wholesale_product` AS A
                JOIN products AS B ON A.product_id = B.id
                JOIN product_units AS C ON B.product_unit = C.id
                JOIN wholesale AS D ON A.wholesale_id = D.id 
            WHERE
                D.STATUS = 'complete' 
            GROUP BY
                B.id, A.product_id, C.abbreviation
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
                LEFT JOIN (
                    SELECT
                        product_id,
                        quantity,
                        avg_price 
                    FROM
                        stock_in UNION
                    SELECT
                        product_id,
                        quantity,
                        price 
                    FROM
                        wholesale_product
                        JOIN wholesale ON wholesale_product.wholesale_id = wholesale.id 
                    WHERE
                        wholesale.`status` = 'complete' 
                        AND product_id != 0
                ) AS B ON A.id = B.product_id
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
        DB::statement("DROP VIEW IF EXISTS sortir_view");
        DB::statement("DROP VIEW IF EXISTS view_wholesale");
    }
};
