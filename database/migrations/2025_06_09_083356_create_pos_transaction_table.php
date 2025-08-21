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
            $table->string('uuid');
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
            $table->text('note')->nullable();
            $table->integer('voucher')->nullable()->default(0);
            $table->integer('voucher_qty')->nullable()->default(0);
            $table->unsignedBigInteger('deposito_id')->nullable();
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
            $table->decimal('exp_value');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('pos_payment', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
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

        DB::table('pos_transaction')->insert([
            [
                'uuid' => Str::uuid(),
                'customer_id' => 1,
                'date' => '2025-07-01',
                'invoice_number' => 'INV202506001',
                'total' => 20000,
                'paid' => 30000,
                'return' => 10000,
                'payment_method' => 1,
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'customer_id' => 2,
                'date' => '2025-07-02',
                'invoice_number' => 'INV202506002',
                'total' => 160000,
                'paid' => 200000,
                'return' => 40000,
                'payment_method' => 2,
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'customer_id' => 3,
                'date' => '2025-07-03',
                'invoice_number' => 'INV202506003',
                'total' => 50000,
                'paid' => 50000,
                'return' => 0,
                'payment_method' => 1,
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'customer_id' => 4,
                'date' => '2025-07-04',
                'invoice_number' => 'INV202506004',
                'total' => 75000,
                'paid' => 80000,
                'return' => 5000,
                'payment_method' => 2,
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'customer_id' => 5,
                'date' => '2025-07-05',
                'invoice_number' => 'INV202506005',
                'total' => 120000,
                'paid' => 120000,
                'return' => 0,
                'payment_method' => 1,
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'customer_id' => 6,
                'date' => '2025-07-06',
                'invoice_number' => 'INV202506006',
                'total' => 90000,
                'paid' => 100000,
                'return' => 10000,
                'payment_method' => 2,
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'customer_id' => 1,
                'date' => '2025-07-06',
                'invoice_number' => 'INV202506007',
                'total' => 90000,
                'paid' => 100000,
                'return' => 10000,
                'payment_method' => 2,
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('pos_transaction_detail')->insert([
            [
                'pos_id' => 1,
                'product_id' => 1,
                'quantity' => 2,
                'price' => 10000,
                'subtotal' => 20000,
                'discount' => 500,
                'exp_value' => 2000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pos_id' => 1,
                'product_id' => 2,
                'quantity' => 1,
                'price' => 8000,
                'subtotal' => 8000,
                'discount' => 200,
                'exp_value' => 1600,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pos_id' => 2,
                'product_id' => 3,
                'quantity' => 3,
                'price' => 40000,
                'subtotal' => 120000,
                'discount' => 1000,
                'exp_value' => 24000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pos_id' => 2,
                'product_id' => 4,
                'quantity' => 2,
                'price' => 20000,
                'subtotal' => 40000,
                'discount' => 5000,
                'exp_value' => 8000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pos_id' => 3,
                'product_id' => 1,
                'quantity' => 1,
                'price' => 50000,
                'subtotal' => 50000,
                'discount' => 0,
                'exp_value' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pos_id' => 4,
                'product_id' => 2,
                'quantity' => 5,
                'price' => 15000,
                'subtotal' => 75000,
                'discount' => 2500,
                'exp_value' => 15000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pos_id' => 5,
                'product_id' => 3,
                'quantity' => 2,
                'price' => 60000,
                'subtotal' => 120000,
                'discount' => 0,
                'exp_value' => 24000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pos_id' => 6,
                'product_id' => 4,
                'quantity' => 3,
                'price' => 30000,
                'subtotal' => 90000,
                'discount' => 3000,
                'exp_value' => 18000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pos_id' => 2,
                'product_id' => 3,
                'quantity' => 3,
                'price' => 40000,
                'subtotal' => 120000,
                'discount' => 3000,
                'exp_value' => 24000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pos_id' => 2,
                'product_id' => 1,
                'quantity' => 4,
                'price' => 40000,
                'subtotal' => 160000,
                'discount' => 5000,
                'exp_value' => 32000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pos_id' => 2,
                'product_id' => 3,
                'quantity' => 3,
                'price' => 40000,
                'subtotal' => 120000,
                'discount' => 3000,
                'exp_value' => 24000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pos_id' => 2,
                'product_id' => 1,
                'quantity' => 4,
                'price' => 40000,
                'subtotal' => 160000,
                'discount' => 5000,
                'exp_value' => 32000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pos_id' => 7,
                'product_id' => 1,
                'quantity' => 4,
                'price' => 40000,
                'subtotal' => 160000,
                'discount' => 5000,
                'exp_value' => 32000,
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
        Schema::dropIfExists('pos_transaction');
        Schema::dropIfExists('pos_transaction_detail');
        Schema::dropIfExists('pos_payment');
    }
};
