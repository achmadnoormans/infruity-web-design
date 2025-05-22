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
        $sql = file_get_contents(database_path('sql/views.sql'));

        foreach (array_filter(array_map('trim', explode(';', $sql))) as $query) {
            if ($query) {
                DB::statement($query);
            }
        }
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
