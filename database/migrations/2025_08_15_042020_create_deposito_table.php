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
        Schema::create('deposito', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->date('deposito_date');
            $table->date('start_period');
            $table->date('end_period');
            $table->integer('voucher');
            $table->integer('voucher_qty');
            $table->unsignedBigInteger('tier_id');
            $table->integer('exp');
            $table->bigInteger('deposito');
            $table->timestamps();
        });

        DB::table('deposito')->insert([
            [
                'customer_id' => 1,
                'voucher' => 100000,
                'voucher_qty' => 100,
                'tier_id' => 4,
                'exp' => 150000,
                'deposito_date' => now(),
                'start_period' => now(),
                'end_period' => now()->addDays(90),
                'deposito' => 10000000,
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
        Schema::dropIfExists('deposito');
    }
};
