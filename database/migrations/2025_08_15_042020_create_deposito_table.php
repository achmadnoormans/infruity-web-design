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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposito');
    }
};
