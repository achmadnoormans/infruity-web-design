<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS view_wholesale");
        
        DB::statement("
            CREATE VIEW view_wholesale AS
            SELECT 
                wholesale.id AS id,
                wholesale.status AS status,
                supplier.name AS supplier_name,
                supplier.pic_name AS pic_name,
                supplier.pic_whatsapp AS whatsapp,
                wholesale.order_date,
                COUNT(wholesale_product.id) AS total_product
            FROM wholesale
            JOIN supplier ON supplier.id = wholesale.supplier_id
            JOIN wholesale_product ON wholesale_product.wholesale_id = wholesale.id
            GROUP BY 
                wholesale.id, 
                wholesale.status, 
                supplier.name,
                supplier.pic_name,
                supplier.pic_whatsapp,
                wholesale.order_date
        ");
    }

    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS view_wholesale");
    }
};
