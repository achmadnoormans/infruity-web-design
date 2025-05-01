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
        Schema::create('handling', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('handling')->insert([
            [
                'name' => 'Packing',
                'description' => 'Packing dengan plastik.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Packing Kayu',
                'description' => 'Packing dengan kayu.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Packing Kardus',
                'description' => 'Packing dengan kardus.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Packing Besi',
                'description' => 'Packing dengan besi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Packing Kaca',
                'description' => 'Packing dengan kaca.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Packing Styrofoam',
                'description' => 'Packing dengan styrofoam.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Packing Bubble Wrap',
                'description' => 'Packing dengan bubble wrap.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Packing Kain',
                'description' => 'Packing dengan kain.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Packing Plastik',
                'description' => 'Packing dengan plastik.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Packing Kertas',
                'description' => 'Packing dengan kertas.',
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
        Schema::dropIfExists('handling');
    }
};
