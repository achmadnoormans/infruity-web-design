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
        Schema::create('wholesale_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wholesale_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->integer('price')->nullable();
            $table->integer('total_price')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->enum('status', ['draft', 'processing', 'complete'])->default('processing');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        $data = [];

        // Ambil semua id wholesale
        $wholesaleIds = DB::table('wholesale')->pluck('id');

        foreach ($wholesaleIds as $wholesaleId) {
            // Set supplier_id sesuai dengan wholesale
            $supplierId = DB::table('wholesale')->where('id', $wholesaleId)->value('supplier_id');

            // Random jumlah item per wholesale (1-5 produk misalnya)
            $numProducts = rand(1, 5);

            for ($i = 1; $i <= $numProducts; $i++) {
                $productId = rand(1, 10); // anggap product_id max 10
                $quantity = rand(5, 20);
                $price = rand(500, 2000);
                $totalPrice = $quantity * $price;

                $data[] = [
                    'wholesale_id' => $wholesaleId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_price' => $totalPrice,
                    'supplier_id' => $supplierId,
                    'status' => rand(0, 1) ? 'processing' : 'complete',
                    'created_by' => $supplierId,
                    'updated_by' => $supplierId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('wholesale_product')->insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wholesale_product');
    }
};
