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
        Schema::create('stock_out_transaction', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->unsignedBigInteger('type_id');
            $table->date('date');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->decimal('avg_price', 10, 2);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('stock_out_transaction')->insert([
            [
                'code' => 'SO2505001',
                'type_id' => 1,
                'date' => date('Y-m-d'),
                'product_id' => 1,
                'quantity' => 1,
                'avg_price' => 1000,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SO2505002',
                'type_id' => 2,
                'date' => date('Y-m-d'),
                'product_id' => 5,
                'quantity' => 2,
                'avg_price' => 1000,
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
        Schema::dropIfExists('stock_out_transaction');
    }
};
