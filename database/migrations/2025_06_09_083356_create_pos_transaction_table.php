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
        Schema::create('pos_transaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->date('date')->nullable();
            $table->string('invoice_number')->nullable();
            $table->integer('total')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('ongkir')->nullable()->default(0);
            $table->enum('ongkir_status', ['draft', 'delivered'])->default('draft');
            $table->date('ongkir_date')->nullable();
            $table->time('ongkir_time')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->integer('paid')->nullable();
            $table->integer('return')->nullable();
            $table->integer('payment_method')->nullable();
            $table->enum('status', ['draft', 'paid', 'debt', 'canceled'])->default('draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('pos_transaction_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pos_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('quantity', 10, 2);
            $table->integer('price');
            $table->integer('discount');
            $table->integer('subtotal');
            $table->integer('exp');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('pos_payment', function (Blueprint $table) {
            $table->id();
            $table->string('nota_number')->nullable();
            $table->unsignedBigInteger('pos_id');
            $table->integer('total');            
            $table->integer('remaining')->nullable()->default(0);
            $table->integer('return')->nullable()->default(0);
            $table->integer('payment_method');
            $table->integer('branch_id')->nullable();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        // DB::table('pos_transaction')->insert([
        //     [
        //         'customer_id' => 1,
        //         'date' => date('Y-m-d'),
        //         'invoice_number' => 'INV202506001',
        //         'total' => 20000,
        //         'paid' => 30000,
        //         'return' => 10000,
        //         'payment_method' => 1,
        //         'status' => 'paid',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'customer_id' => 2,
        //         'date' => date('Y-m-d'),
        //         'invoice_number' => 'INV202506002',
        //         'total' => 160000,
        //         'paid' => 200000,
        //         'return' => 40000,
        //         'payment_method' => 2,
        //         'status' => 'paid',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        // ]);

        // DB::table('pos_transaction_detail')->insert([
        //     [
        //         'pos_id' => 1,
        //         'product_id' => 1,
        //         'quantity' => 2,
        //         'price' => 10000,
        //         'subtotal' => 20000,
        //         'discount' => 500,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'pos_id' => 2,
        //         'product_id' => 1,
        //         'quantity' => 4,
        //         'price' => 40000,
        //         'subtotal' => 160000,
        //         'discount' => 5000,
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
        Schema::dropIfExists('pos_transaction');
        Schema::dropIfExists('pos_transaction_detail');
        Schema::dropIfExists('pos_payment');
    }
};
