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
        Schema::create('department', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('department')->insert([
            [
                'name' => 'Human Resource',
                'code' => 'HR',
                'description' => 'Mengatur Karyawan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Finance',
                'code' => 'FIN',
                'description' => 'Mengatur Keuangan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Marketing',
                'code' => 'MKT',
                'description' => 'Mengatur Pemasaran.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'IT Support',
                'code' => 'IT',
                'description' => 'Mengatur IT Support.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sales',
                'code' => 'SLS',
                'description' => 'Mengatur Penjualan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Logistics',
                'code' => 'LOG',
                'description' => 'Mengatur Logistik.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Customer Service',
                'code' => 'CS',
                'description' => 'Mengatur Layanan Pelanggan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Research and Development',
                'code' => 'R&D',
                'description' => 'Mengatur Penelitian dan Pengembangan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Production',
                'code' => 'PRD',
                'description' => 'Mengatur Produksi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Quality Control',
                'code' => 'QC',
                'description' => 'Mengatur Kontrol Kualitas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Legal',
                'code' => 'LGL',
                'description' => 'Mengatur Hukum.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Public Relations',
                'code' => 'PR',
                'description' => 'Mengatur Hubungan Masyarakat.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Administration',
                'code' => 'ADM',
                'description' => 'Mengatur Administrasi.',
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
        Schema::dropIfExists('department');
    }
};
