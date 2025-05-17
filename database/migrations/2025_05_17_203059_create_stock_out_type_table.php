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
        Schema::create('stock_out_type', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');  
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('stock_out_type')->insert([
            [
                'name' => 'Penyesuaian Stok Keluaran',
                'description' => 'Penyesuaian Stok Keluaran',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sample / Promos (SMP)',
                'description' => 'Sample / Promos (SMP)',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Konsumsi Internal (KNS)',
                'description' => 'Konsumsi Internal (KNS)',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_out_type');
    }
};
