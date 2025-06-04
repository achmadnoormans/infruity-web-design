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
        Schema::create('product_receipt', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->unsignedBigInteger('receipt_id');
            $table->integer('product_id');
            $table->integer('product_receipt_id');
             $table->decimal('quantity', 10, 2);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        // DB::table('product_receipt')->insert([
        //     [
        //         'receipt_id' => 1,
        //         'product_id' => 9,
        //         'product_receipt_id' => 1,
        //         'quantity' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'receipt_id' => 1,
        //         'product_id' => 9,
        //         'product_receipt_id' => 6,
        //         'quantity' => 7,
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
        Schema::dropIfExists('product_receipt');
    }
};
