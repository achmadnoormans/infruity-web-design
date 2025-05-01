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
        Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('abbreviation');
            $table->text('image')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('product_units')->insert([
            [
                'name' => 'Kilogram',
                'abbreviation' => 'Kg',
                'description' => 'Satuan Umum.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gram',
                'abbreviation' => 'g',
                'description' => 'Satuan Umum.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pcs',
                'abbreviation' => 'Pcs',
                'description' => 'Satuan Umum.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Box',
                'abbreviation' => 'Box',
                'description' => 'Satuan Umum.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lusin',
                'abbreviation' => 'Lus',
                'description' => 'Satuan Umum.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Liter',
                'abbreviation' => 'Ltr',
                'description' => 'Satuan Umum.',
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
        Schema::dropIfExists('product_units');
    }
};
