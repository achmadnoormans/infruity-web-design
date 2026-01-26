<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $wilayah       = file_get_contents(database_path('wilayah_indonesia.sql'));
        $sql           = file_get_contents(database_path('sql/views.sql'));
        $report        = file_get_contents(database_path('sql/report.sql'));
        $function      = file_get_contents(database_path('sql/function.sql'));
        $product_stock = file_get_contents(database_path('sql/product_stock.sql'));
        $product_hpp   = file_get_contents(database_path('sql/product_hpp.sql'));

        foreach (array_filter(array_map('trim', explode(';', $wilayah))) as $query) {
            if ($query) {
                DB::statement($query);
            }
        }

        foreach (array_filter(array_map('trim', explode(';', $sql))) as $query) {
            if ($query) {
                DB::statement($query);
            }
        }
        foreach (array_filter(array_map('trim', explode(';', $report))) as $query) {
            if ($query) {
                DB::statement($query);
            }
        }
        foreach (array_filter(array_map('trim', explode(';', $product_stock))) as $query) {
            if ($query) {
                DB::statement($query);
            }
        }
        foreach (array_filter(array_map('trim', explode(';', $product_hpp))) as $query) {
            if ($query) {
                DB::statement($query);
            }
        }

        $function = File::get(database_path('sql/function.sql'));
        DB::unprepared($function);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS product_stock");
        DB::statement("DROP VIEW IF EXISTS product_hpp");
        DB::statement("DROP VIEW IF EXISTS transaction_stock");
        DB::statement("DROP VIEW IF EXISTS sortir_view");
        DB::statement("DROP VIEW IF EXISTS view_wholesale");
        DB::statement("DROP VIEW IF EXISTS vw_customer_transaction");
        DB::statement("DROP PROCEDURE IF EXISTS get_customer_report");
    }
};
