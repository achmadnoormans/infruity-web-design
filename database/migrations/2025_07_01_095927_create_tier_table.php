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
        Schema::create('crm_tier', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('level');
            $table->integer('exp');
            $table->string('icon')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('crm_tier')->insert([
            [
                'name' => 'Bronze',
                'level' => 1,
                'exp' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Silver',
                'level' => 2,
                'exp' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gold',
                'level' => 3,
                'exp' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Platinum',
                'level' => 4,
                'exp' => 150000,
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
        Schema::dropIfExists('tier');
    }
};
