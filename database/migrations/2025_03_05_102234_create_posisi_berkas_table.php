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
        Schema::create('posisi_berkas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_status');
            $table->integer('id_layanan')->nullable();
            $table->integer('posisi')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        \DB::table('posisi_berkas')->delete();

        \DB::table('posisi_berkas')->insert(array(
            // Fotocopy IPT
            array(
                'id_layanan' => 1,
                'id_status' => 1,
                'posisi' => 8,
            ),
            array(
                'id_layanan' => 1,
                'id_status' => 2,
                'posisi' => 9,
            ),
            array(
                'id_layanan' => 1,
                'id_status' => 3,
                'posisi' => 10,
            ),
            array(
                'id_layanan' => 1,
                'id_status' => 4,
                'posisi' => 13,
            ),
            array(
                'id_layanan' => 1,
                'id_status' => 5,
                'posisi' => 11,
            ),
            array(
                'id_layanan' => 1,
                'id_status' => 6,
                'posisi' => 5,
            ),
            array(
                'id_layanan' => 1,
                'id_status' => 7,
                'posisi' => 6,
            ),
            array(
                'id_layanan' => 1,
                'id_status' => 8,
                'posisi' => 7,
            ),
            array(
                'id_layanan' => 1,
                'id_status' => 9,
                'posisi' => 8,
            ),
            array(
                'id_layanan' => 1,
                'id_status' => 10,
                'posisi' => 8,
            ),
            array(
                'id_layanan' => 1,
                'id_status' => 11,
                'posisi' => 99,
            ),

            // Rekom Iklan Mandiri
            array(
                'id_layanan' => 2,
                'id_status' => 1,
                'posisi' => 8,
            ),
            array(
                'id_layanan' => 2,
                'id_status' => 2,
                'posisi' => 9,
            ),
            array(
                'id_layanan' => 2,
                'id_status' => 3,
                'posisi' => 10,
            ),
            array(
                'id_layanan' => 2,
                'id_status' => 4,
                'posisi' => 11,
            ),
            array(
                'id_layanan' => 2,
                'id_status' => 5,
                'posisi' => 11,
            ),
            array(
                'id_layanan' => 2,
                'id_status' => 6,
                'posisi' => 5,
            ),
            array(
                'id_layanan' => 2,
                'id_status' => 7,
                'posisi' => 6,
            ),
            array(
                'id_layanan' => 2,
                'id_status' => 8,
                'posisi' => 7,
            ),
            array(
                'id_layanan' => 2,
                'id_status' => 9,
                'posisi' => 8,
            ),
            array(
                'id_layanan' => 2,
                'id_status' => 10,
                'posisi' => 8,
            ),
            array(
                'id_layanan' => 2,
                'id_status' => 11,
                'posisi' => 99,
            ),

            // Balik Nama Mandiri
            array(
                'id_layanan' => 3,
                'id_status' => 1,
                'posisi' => 8,
            ),
            array(
                'id_layanan' => 3,
                'id_status' => 2,
                'posisi' => 9,
            ),
            array(
                'id_layanan' => 3,
                'id_status' => 3,
                'posisi' => 10,
            ),
            array(
                'id_layanan' => 3,
                'id_status' => 4,
                'posisi' => 11,
            ),
            array(
                'id_layanan' => 3,
                'id_status' => 5,
                'posisi' => 11,
            ),
            array(
                'id_layanan' => 3,
                'id_status' => 6,
                'posisi' => 5,
            ),
            array(
                'id_layanan' => 3,
                'id_status' => 7,
                'posisi' => 6,
            ),
            array(
                'id_layanan' => 3,
                'id_status' => 8,
                'posisi' => 7,
            ),
            array(
                'id_layanan' => 3,
                'id_status' => 9,
                'posisi' => 8,
            ),
            array(
                'id_layanan' => 3,
                'id_status' => 10,
                'posisi' => 8,
            ),
            array(
                'id_layanan' => 3,
                'id_status' => 11,
                'posisi' => 99,
            ),

            // Balik Nama Kolektif
            array(
                'id_layanan' => 5,
                'id_status' => 1,
                'posisi' => 8,
            ),
            array(
                'id_layanan' => 5,
                'id_status' => 2,
                'posisi' => 9,
            ),
            array(
                'id_layanan' => 5,
                'id_status' => 3,
                'posisi' => 10,
            ),
            array(
                'id_layanan' => 5,
                'id_status' => 4,
                'posisi' => 11,
            ),
            array(
                'id_layanan' => 5,
                'id_status' => 5,
                'posisi' => 5,
            ),
            array(
                'id_layanan' => 5,
                'id_status' => 6,
                'posisi' => 6,
            ),
            array(
                'id_layanan' => 5,
                'id_status' => 7,
                'posisi' => 7,
            ),
            array(
                'id_layanan' => 5,
                'id_status' => 8,
                'posisi' => 2,
            ),
            array(
                'id_layanan' => 5,
                'id_status' => 9,
                'posisi' => 2,
            ),
            array(
                'id_layanan' => 5,
                'id_status' => 10,
                'posisi' => 99,
            ),

            // Permohonan Status Tanah
            array(
                'id_layanan' => 7,
                'id_status' => 1,
                'posisi' => 8,
            ),
            array(
                'id_layanan' => 7,
                'id_status' => 2,
                'posisi' => 6,
            ),
            array(
                'id_layanan' => 7,
                'id_status' => 7,
                'posisi' => 5,
            ),
            array(
                'id_layanan' => 7,
                'id_status' => 8,
                'posisi' => 7,
            ),
            array(
                'id_layanan' => 7,
                'id_status' => 6,
                'posisi' => 11,
            ),
            array(
                'id_layanan' => 7,
                'id_status' => 5,
                'posisi' => 9,
            ),
            array(
                'id_layanan' => 7,
                'id_status' => 3,
                'posisi' => 10,
            ),
            array(
                'id_layanan' => 7,
                'id_status' => 4,
                'posisi' => 3,
            ),
            array(
                'id_layanan' => 7,
                'id_status' => 9,
                'posisi' => 3,
            ),
            array(
                'id_layanan' => 7,
                'id_status' => 10,
                'posisi' => 8,
            ),
            array(
                'id_layanan' => 7,
                'id_status' => 11,
                'posisi' => 99,
            ),

            // Permohonan Pengurangan IPT
            array(
                'id_layanan' => 8,
                'id_status' => 1,
                'posisi' => 8,
            ),
            array(
                'id_layanan' => 8,
                'id_status' => 2,
                'posisi' => 9,
            ),
            array(
                'id_layanan' => 8,
                'id_status' => 3,
                'posisi' => 10,
            ),
            array(
                'id_layanan' => 8,
                'id_status' => 4,
                'posisi' => 11,
            ),
            array(
                'id_layanan' => 8,
                'id_status' => 5,
                'posisi' => 5,
            ),
            array(
                'id_layanan' => 8,
                'id_status' => 6,
                'posisi' => 6,
            ),
            array(
                'id_layanan' => 8,
                'id_status' => 7,
                'posisi' => 7,
            ),
            array(
                'id_layanan' => 8,
                'id_status' => 8,
                'posisi' => 2,
            ),
            array(
                'id_layanan' => 8,
                'id_status' => 9,
                'posisi' => 2,
            ),
            array(
                'id_layanan' => 8,
                'id_status' => 10,
                'posisi' => 99,
            ),
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posisi_berkas');
    }
};
