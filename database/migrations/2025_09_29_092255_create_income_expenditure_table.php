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
        Schema::create('expenditure', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->date('date')->nullable();
            $table->string('invoice_number')->nullable();
            $table->decimal('total', 15, 2)->nullable();
            $table->decimal('discount', 15, 2)->nullable();
            $table->decimal('paid', 15, 2)->nullable();
            $table->decimal('return', 15, 2)->nullable();
            $table->integer('payment_method')->nullable();
            $table->enum('status', ['draft', 'paid', 'debt', 'temp', 'canceled', 'pending'])->default('draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('expenditure_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expenditure_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('parcel_id')->nullable();
            $table->unsignedBigInteger('production_id')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->decimal('price', 15, 2);
            $table->decimal('discount', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->enum('type', ['product', 'parcel'])->default('product');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('expenditure_payment', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('nota_number')->nullable();
            $table->unsignedBigInteger('expenditure_id');
            $table->decimal('total', 15, 2);
            $table->decimal('remaining', 15, 2)->nullable()->default(0);
            $table->decimal('return', 15, 2)->nullable()->default(0);
            $table->string('payment_method')->nullable();
            $table->string('payment_method_id')->nullable();
            $table->string('payment_amount')->nullable();
            $table->integer('branch_id')->nullable();
            $table->date('date')->nullable();
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
        Schema::dropIfExists('expenditure');
        Schema::dropIfExists('expenditure_detail');
        Schema::dropIfExists('expenditure_payment');
    }
};
