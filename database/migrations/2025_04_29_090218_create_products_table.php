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
            $table->text('image')->nullable();
            $table->text('description')->nullable();
            $table->text('sku')->nullable();
            $table->text('barcode')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->integer('product_unit')->default(1);
            $table->integer('limit')->default(1);
            $table->text('handling')->nullable();
            $table->text('status')->nullable();
            $table->integer('level')->default(1);
            $table->integer('parent_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('products')->insert([
            [
                'name' => 'Apel Fuji',
                'description' => 'Apel manis dan renyah asal Jepang.',
                'price' => 50000,
                'stock' => 100,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pisang Cavendish',
                'description' => 'Pisang kuning segar siap makan.',
                'price' => 30000,
                'stock' => 200,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jeruk Sunkist',
                'description' => 'Jeruk impor dengan rasa manis dan asam seimbang.',
                'price' => 45000,
                'stock' => 150,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mangga Harum Manis',
                'description' => 'Mangga lokal dengan aroma harum dan rasa legit.',
                'price' => 40000,
                'stock' => 120,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Anggur Red Globe',
                'description' => 'Anggur besar dan manis cocok untuk konsumsi langsung.',
                'price' => 60000,
                'stock' => 80,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Apel Fuji Bs 1',
                'description' => 'Apel manis dan renyah asal Jepang.',
                'price' => 50000,
                'stock' => 100,
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Apel Fuji Bs 2',
                'description' => 'Apel manis dan renyah asal Jepang.',
                'price' => 50000,
                'stock' => 100,
                'parent_id' => 1,
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
