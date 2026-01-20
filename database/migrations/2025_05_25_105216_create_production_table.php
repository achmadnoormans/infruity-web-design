<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('production', function (Blueprint $table) {
            $table->id();
            $table->string('production_number', 20); // Kolom untuk nomor pesanan
            $table->unsignedBigInteger('product_id')->nullable();
            $table->decimal('quantity', 10, 2)->default(1);
            $table->date('production_date')->nullable();
            $table->enum('status', ['draft', 'posting', 'complete', 'temp'])->default('posting');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
        
        // DB::table('production')->insert([
        //     [
        //         'production_number' => 'PRO' . now()->format('Ym') . '001',
        //         'product_id' => 9, // ID produk Apel Frozen
        //         'quantity' => 1,
        //         'production_date' => now(),
        //         'status' => 'posting',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],   
        // ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production');
    }
};
