<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            $table->integer('price')->nullable()->default(0);
            $table->decimal('stock', 10, 2)->default(0);
            $table->integer('product_unit')->default(1);
            $table->integer('limit')->default(1);
            $table->text('handling')->nullable();
            $table->enum('status', ['receipt', 'no-receipt'])->default('no-receipt');
            $table->integer('level')->default(1);
            $table->integer('parent_id')->nullable();
            $table->integer('is_variant')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('direct_stock')->nullable();
            $table->integer('hpp')->nullable();
            $table->date('hpp_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('products')->insert([
            [
                'name' => 'Apel Fuji',
                'description' => 'Apel manis dan renyah asal Jepang.',
                'price' => 50000,
                'category_id' => 1,
                'direct_stock' => null,
                'status' => 'receipt',
                'hpp' => 40000,
                'hpp_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pisang Cavendish',
                'description' => 'Pisang kuning segar siap makan.',
                'price' => 30000,
                'category_id' => 4,
                'direct_stock' => null,
                'status' => 'no-receipt',
                'hpp' => 25000,
                'hpp_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jeruk Sunkist',
                'description' => 'Jeruk impor dengan rasa manis dan asam seimbang.',
                'price' => 45000,
                'category_id' => 5,
                'direct_stock' => null,
                'status' => 'no-receipt',
                'hpp' => 35000,
                'hpp_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mangga Harum Manis',
                'description' => 'Mangga lokal dengan aroma harum dan rasa legit.',
                'price' => 40000,
                'category_id' => 2,
                'direct_stock' => null,
                'status' => 'no-receipt',
                'hpp' => 30000,
                'hpp_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Anggur Red Globe',
                'description' => 'Anggur besar dan manis cocok untuk konsumsi langsung.',
                'price' => 60000,
                'category_id' => 3,
                'direct_stock' => null,
                'status' => 'no-receipt',
                'hpp' => 45000,
                'hpp_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Apel Fuji Bs 1',
                'description' => 'Apel manis dan renyah asal Jepang.',
                'price' => 50000,
                'category_id' => 1,
                'direct_stock' => null,
                'status' => 'no-receipt',
                'hpp' => 40000,
                'hpp_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Apel Fuji Bs 2',
                'description' => 'Apel manis dan renyah asal Jepang.',
                'price' => 50000,
                'category_id' => 1,
                'direct_stock' => null,
                'status' => 'no-receipt',
                'hpp' => 40000,
                'hpp_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Anggur Lgs',
                'description' => 'Anggur manis dan renyah asal Jepang.',
                'price' => 50000,
                'category_id' => 3,
                'direct_stock' => null,
                'status' => 'no-receipt',
                'hpp' => 40000,
                'hpp_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Aple Frozen',
                'description' => 'Apel yang dibekukan.',
                'price' => 50000,
                'category_id' => 1,
                'direct_stock' => null,
                'status' => 'receipt',
                'hpp' => 40000,
                'hpp_date' => now(),
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
