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
        Schema::create('sortir_transaction', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->date('date')->nullable();
            $table->string('invoice_number')->nullable();
            $table->decimal('total', 15, 2)->nullable();
            $table->enum('status', ['draft', 'paid', 'debt', 'temp', 'canceled', 'pending'])->default('draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('sortir_transaction_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sortir_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('quantity', 10, 2);
            $table->decimal('price', 15, 2);
            $table->decimal('discount', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sortir_transaction');
        Schema::dropIfExists('sortir_detail');
    }
};
