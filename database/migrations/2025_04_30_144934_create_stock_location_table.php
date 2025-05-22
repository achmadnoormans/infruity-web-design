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
        Schema::create('stock_location', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('stock_location')->insert([
            [
                'name' => 'Gudang Utama',
                'address' => 'Jl. Raya No. 1, Gresik',
                'description' => 'Hanya untuk barang berat.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gudang Cabang',
                'address' => 'Jl. Raya No. 2, Surabaya',
                'description' => 'Untuk barang ringan dan cepat habis.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Toko Utama',
                'address' => 'Jl. Raya No. 3, Malang',
                'description' => 'Untuk barang yang sudah siap jual.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Toko Cabang',
                'address' => 'Jl. Raya No. 4, Sidoarjo',
                'description' => 'Untuk barang yang sudah siap jual.',
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
        Schema::dropIfExists('stock_location');
    }
};
