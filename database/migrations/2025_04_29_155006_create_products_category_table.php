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
        Schema::create('products_category', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('image')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('products_category')->insert([
            [
                'name' => 'Apel Fuji',
                'description' => 'Apel manis dan renyah asal Jepang.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mangga',
                'description' => 'Mangga lokal dengan aroma harum dan rasa legit.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Anggur',
                'description' => 'Anggur besar dan manis cocok untuk konsumsi langsung.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pisang',
                'description' => 'Pisang kuning segar siap makan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jeruk',
                'description' => 'Jeruk nipis dengan rasa manis dan segar.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_category');
    }
};
