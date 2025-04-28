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
        Schema::create('m_layanan', function (Blueprint $table) {
            $table->integer('id_layanan', true);
            $table->string('nm_layanan')->nullable();
            $table->integer('id_user');
            $table->boolean('status')->default(true);
            $table->string('type')->nullable();
            $table->string(column: 'keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        \DB::table('m_layanan')->insert(array(
            0 => array(
                'id_layanan' => 1,
                'nm_layanan' => 'SURAT KETERANGAN PERSIL',
                'id_user' => '1',
                'status' => true,
                'type' => 'surat-keterangan',
                'keterangan' => 'Untuk Permohonan Laporan Ke Kepolisian',
            ),
            1 => array(
                'id_layanan' => 2,
                'nm_layanan' => 'PERMOHONAN PENGUMUMAN (IPT HILANG)',
                'id_user' => '1',
                'status' => true,
                'type' => 'surat-keterangan',
                'keterangan' => 'Untuk Permohonan Pengumuman IPT Hilang Di Koran / Di Lokasi Persil',
            ),
            2 => array(
                'id_layanan' => 3,
                'nm_layanan' => 'PERMOHONAN PENGUMUMAN (IPT BALIKNAMA)',
                'id_user' => '1',
                'status' => true,
                'type' => 'surat-keterangan',
                'keterangan' => 'Untuk Permohonan Pengumuman Balik Nama IPT Di Koran / Di Lokasi Persil',
            ),
            3 => array(
                'id_layanan' => 4,
                'nm_layanan' => 'REKOM IKLAN KOLEKTIF',
                'id_user' => '1',
                'status' => false,
                'type' => 'surat-keterangan',
                'keterangan' => '-',
            ),
            4 => array(
                'id_layanan' => 5,
                'nm_layanan' => 'BALIK NAMA KOLEKTIF',
                'id_user' => '1',
                'status' => false,
                'type' => 'surat-keterangan',
                'keterangan' => '-',
            ),
            5 => array(
                'id_layanan' => 6,
                'nm_layanan' => 'PERMOHONAN PENCABUTAN',
                'id_user' => '1',
                'status' => true,
                'type' => 'surat-keterangan',
                'keterangan' => '-',
            ),
            6 => array(
                'id_layanan' => 7,
                'nm_layanan' => 'PERMOHONAN STATUS TANAH',
                'id_user' => '1',
                'status' => true,
                'type' => 'surat-keterangan',
                'keterangan' => '-',
            ),
            7 => array(
                'id_layanan' => 8,
                'nm_layanan' => 'IPT PENGURANGAN',
                'id_user' => '1',
                'status' => true,
                'type' => 'ipt',
                'keterangan' => 'Untuk Permohonan Keringanan / Pengurangan Retribusi IPT',
            ),
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_layanan');
    }
};
