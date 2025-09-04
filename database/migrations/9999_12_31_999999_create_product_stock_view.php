<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sql = file_get_contents(database_path('sql/views.sql'));
        $function = file_get_contents(database_path('sql/function.sql'));

        foreach (array_filter(array_map('trim', explode(';', $sql))) as $query) {
            if ($query) {
                DB::statement($query);
            }
        }
        $sql = File::get(database_path('sql/function.sql'));
        DB::unprepared($sql);
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
        DB::statement("DROP PROCEDURE IF EXISTS get_customer_report");
    }
};
