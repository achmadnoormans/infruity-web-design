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
        Schema::create('wholesale_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wholesale_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->integer('hpp')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('wholesale_product')->insert([
            [
                'wholesale_id' => 1,
                'product_id' => 1,
                'quantity' => 10,
                'hpp' => 10000,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'wholesale_id' => 1,
                'product_id' => 2,
                'quantity' => 5,
                'hpp' => 10000,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'wholesale_id' => 2,
                'product_id' => 3,
                'quantity' => 20,
                'hpp' => null,
                'created_by' => 2,
                'updated_by' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'wholesale_id' => 3,
                'product_id' => 1,
                'quantity' => 15,
                'hpp' => 10000,
                'created_by' => 2,
                'updated_by' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'wholesale_id' => 3,
                'product_id' => 4,
                'quantity' => 8,
                'hpp' => 10000,
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
        Schema::dropIfExists('wholesale_product');
    }
};
