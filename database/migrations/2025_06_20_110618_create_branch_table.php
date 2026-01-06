<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('branch', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->string('address')->unique();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('branch')->insert([
            [
                'name'       => 'Kantor Pusat',
                'code'       => 'HQ',
                'address'    => 'Jl. Raya No. 1, Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Cabang Jakarta',
                'code'       => 'JKT',
                'address'    => 'Jl. Sudirman No. 2, Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Cabang Surabaya',
                'code'       => 'SUB',
                'address'    => 'Jl. Diponegoro No. 3, Surabaya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Cabang Bandung',
                'code'       => 'BDG',
                'address'    => 'Jl. Braga No. 4, Bandung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $products  = DB::table('products')->get();
        $branchIds = DB::table('branch')->pluck('id');
        $data      = [];
        foreach ($products as $product) {
            // Set supplier_id sesuai dengan wholesale
            foreach ($branchIds as $value) {
                $data[] = [
                    'product_id' => $product->id,
                    'branch_id'  => $value,
                    'price'      => array_rand(range(1000, 100000), 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

        }
        DB::table('product_branch')->insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch');
    }
};
