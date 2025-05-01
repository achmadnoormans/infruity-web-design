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
        Schema::create('position', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->unsignedBigInteger('department_id');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('position')->insert([
            [
                'name' => 'Kepala HRD',
                'code' => 'J-HRD001',
                'department_id' => 1,
                'description' => 'Mengatur Karyawan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kepala Keuangan',
                'code' => 'J-FIN001',
                'department_id' => 2,
                'description' => 'Mengatur Keuangan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kepala Pemasaran',
                'code' => 'J-MKT001',
                'department_id' => 3,
                'description' => 'Mengatur Pemasaran.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kepala IT Support',
                'code' => 'J-IT001',
                'department_id' => 4,
                'description' => 'Mengatur IT Support.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kepala Penjualan',
                'code' => 'J-SLS001',
                'department_id' => 5,
                'description' => 'Mengatur Penjualan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kepala Logistik',
                'code' => 'J-LOG001',
                'department_id' => 6,
                'description' => 'Mengatur Logistik.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kepala Produksi',
                'code' => 'J-PRO001',
                'department_id' => 7,
                'description' => 'Mengatur Produksi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kepala Gudang',
                'code' => 'J-GUD001',
                'department_id' => 8,
                'description' => 'Mengatur Gudang.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kepala Customer Service',
                'code' => 'J-CS001',
                'department_id' => 9,
                'description' => 'Mengatur Layanan Pelanggan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kepala R&D',
                'code' => 'J-RND001',
                'department_id' => 10,
                'description' => 'Mengatur R&D.',
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
        Schema::dropIfExists('position');
    }
};
