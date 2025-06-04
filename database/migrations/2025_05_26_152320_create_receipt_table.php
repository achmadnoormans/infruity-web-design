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
        Schema::create('receipt', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->unsignedBigInteger('product_id');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        // DB::table('receipt')->insert([
        //     [
        //         'code' => 'RCP202505001',
        //         'product_id' => 9,
        //         'description' => 'Receipt for Apel Frozen',
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
        Schema::dropIfExists('receipt');
    }
};
