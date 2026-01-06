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
        Schema::create('wholesale', function (Blueprint $table) {
            $table->id();
            $table->text('uuid');
            $table->unsignedBigInteger('branch_id');
            $table->string('order_number', 20); // Kolom untuk nomor pesanan
            $table->unsignedBigInteger('supplier_id');
            $table->date('order_date');
            $table->enum('status', ['draft', 'posting'])->default('posting');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        $data = [];

        for ($i = 1; $i <= 25; $i++) {
            $supplierId = rand(1, 3);

            $data[] = [
                'supplier_id'  => $supplierId,
                'order_number' => 'PO' . date('Ym') . str_pad($i, 3, '0', STR_PAD_LEFT),
                'order_date'   => "2025-05-" . str_pad($i, 2, '0', STR_PAD_LEFT),
                'status'       => 'posting',
                'created_by'   => 1, // contoh: sama dengan supplier_id
                'updated_by'   => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        // DB::table('wholesale')->insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wholesale');
    }
};
