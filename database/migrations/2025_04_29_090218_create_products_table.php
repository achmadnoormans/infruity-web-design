<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->timestamps();
        });

        DB::table('products')->insert([
            [
                'name' => 'Apel Fuji',
                'description' => 'Apel manis dan renyah asal Jepang.',
                'price' => 50000,
                'stock' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pisang Cavendish',
                'description' => 'Pisang kuning segar siap makan.',
                'price' => 30000,
                'stock' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jeruk Sunkist',
                'description' => 'Jeruk impor dengan rasa manis dan asam seimbang.',
                'price' => 45000,
                'stock' => 150,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mangga Harum Manis',
                'description' => 'Mangga lokal dengan aroma harum dan rasa legit.',
                'price' => 40000,
                'stock' => 120,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Anggur Red Globe',
                'description' => 'Anggur besar dan manis cocok untuk konsumsi langsung.',
                'price' => 60000,
                'stock' => 80,
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
        Schema::dropIfExists('products');
    }
};
