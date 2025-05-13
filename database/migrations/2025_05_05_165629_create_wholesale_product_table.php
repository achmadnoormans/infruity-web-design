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
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->integer('price')->nullable();
            $table->integer('total_price')->nullable();
            $table->integer('supplier_id');
            $table->enum('status', ['draft', 'processing', 'complete'])->default('processing');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('wholesale_product')->insert([
            [
                'wholesale_id' => 1,
                'product_id' => 1,
                'quantity' => 10,
                'price' => 1000,
                'total_price' => 10000,
                'supplier_id' => 1,
                'status' => 'processing',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'wholesale_id' => 1,
                'product_id' => 1,
                'quantity' => 5,
                'price' => 1000,
                'total_price' => 5000,
                'supplier_id' => 1,
                'status' => 'processing',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'wholesale_id' => 2,
                'product_id' => 1,
                'quantity' => 20,
                'price' => 1000,
                'total_price' => 20000,
                'supplier_id' => 1,
                'status' => 'processing',
                'created_by' => 2,
                'updated_by' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'wholesale_id' => 3,
                'product_id' => 1,
                'quantity' => 15,
                'price' => 1000,
                'total_price' => 15000,
                'supplier_id' => 1,
                'status' => 'processing',
                'created_by' => 2,
                'updated_by' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'wholesale_id' => 3,
                'product_id' => 5,
                'quantity' => 8,
                'price' => 1000,
                'total_price' => 8000,
                'supplier_id' => 1,
                'status' => 'processing',
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
