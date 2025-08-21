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
        Schema::create('crm_tier', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('level');
            $table->integer('exp');
            $table->string('icon')->nullable();
            $table->string('free_product_id')->nullable();
            $table->integer('discount_transaction')->nullable();
            $table->integer('birthday_gift')->nullable();
            $table->integer('combo_promo')->nullable();
            $table->string('style')->nullable(); // New column for style
            $table->integer('minimal_purchase')->nullable(); // New column for minimal purchase
            $table->integer('max_claim')->nullable(); // New column for max claim
            $table->integer('voucher')->nullable();
            $table->bigInteger('deposito')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('crm_tier')->insert([
            [
                'name' => 'Bronze',
                'level' => 1,
                'exp' => 0,
                'style' => 'badge-light-primary',
                'minimal_purchase' => 0,
                'max_claim' => 1,
                'deposito' => 0,
                'voucher' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Silver',
                'level' => 2,
                'exp' => 50000,
                'style' => 'badge-light-secondary',
                'minimal_purchase' => 100000,
                'max_claim' => 1,
                'deposito' => 2500000,
                'voucher' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gold',
                'level' => 3,
                'exp' => 100000,
                'style' => 'badge-light-warning',
                'minimal_purchase' => 200000,
                'max_claim' => 1,
                'deposito' => 5000000,
                'voucher' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Platinum',
                'level' => 4,
                'exp' => 150000,
                'style' => 'badge-light-success',
                'minimal_purchase' => 300000,
                'max_claim' => 1,
                'deposito' => 10000000,
                'voucher' => 100000,
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
        Schema::dropIfExists('crm_tier');
    }
};
