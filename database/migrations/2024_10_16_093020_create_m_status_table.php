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
        Schema::create('m_status', function (Blueprint $table) {
            $table->integer('id_status', true);
            $table->string('nama_status');
            $table->string('icon')->nullable();
            $table->string('class_color')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('m_status_ipt', function (Blueprint $table) {
            $table->integer('id_status', true);
            $table->string('nama_status');
            $table->string('icon')->nullable();
            $table->string('class_color')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        \DB::table('m_status')->delete();
        \DB::table('m_status')->insert(array(

            1 => array(
                'id_status' => 1,
                'nama_status' => 'BERKAS DIKIRIM',
                'icon' => 'check-circle',
                'class_color' => 'info',
            ),
            2 => array(
                'id_status' => 2,
                'nama_status' => 'VALIDASI DOKUMEN',
                'icon' => 'check-circle',
                'class_color' => 'success',
            ),
            3 => array(
                'id_status' => 3,
                'nama_status' => 'PEMBUATAN FILE BAP',
                'icon' => 'loader',
                'class_color' => 'info',
            ),
            4 => array(
                'id_status' => 4,
                'nama_status' => 'PEMBUATAN KONSEP SURAT',
                'icon' => 'loader',
                'class_color' => 'info',
            ),
            5 => array(
                'id_status' => 5,
                'nama_status' => 'PENYELIA SURAT',
                'icon' => 'loader',
                'class_color' => 'info',
            ),
            6 => array(
                'id_status' => 6,
                'nama_status' => 'VALIDASI KETUA ',
                'icon' => 'check-circle',
                'class_color' => 'success',
            ),
            7 => array(
                'id_status' => 7,
                'nama_status' => 'VERIFIKASI KABID',
                'icon' => 'check-circle',
                'class_color' => 'success',
            ),
            8 => array(
                'id_status' => 8,
                'nama_status' => 'VERIFIKASI SEKRETARIS',
                'icon' => 'check-circle',
                'class_color' => 'success',
            ),
            9 => array(
                'id_status' => 9,
                'nama_status' => 'VERIFIKASI KA BPKAD',
                'icon' => 'check-circle',
                'class_color' => 'success',
            ),
            10 => array(
                'id_status' => 10,
                'nama_status' => 'PENOMERAN SURAT',
                'icon' => 'loader',
                'class_color' => 'info',
            ),
            11 => array(
                'id_status' => 11,
                'nama_status' => 'PENGEMBALIAN DOKUMEN & CETAK SURAT',
                'icon' => 'check-circle',
                'class_color' => 'info',
            ),
            12 => array(
                'id_status' => 99,
                'nama_status' => 'REJECT',
                'icon' => 'alert',
                'class_color' => 'danger',
            ),

            13 => array(
                'id_status' => 100,
                'nama_status' => 'DATA BELUM LENGKAP',
                'icon' => 'alert',
                'class_color' => 'danger',
            ),
        ));

        \DB::table('m_status_ipt')->delete();
        \DB::table('m_status_ipt')->insert(array(

            1 => array(
                'id_status' => 1,
                'nama_status' => 'BERKAS DIKIRIM',
                'icon' => 'check-circle',
                'class_color' => 'info',
            ),
            2 => array(
                'id_status' => 2,
                'nama_status' => 'VALIDASI DOKUMEN DAN PEMBUATAN BAP',
                'icon' => 'check-circle',
                'class_color' => 'success',
            ),
            3 => array(
                'id_status' => 3,
                'nama_status' => 'PEMBUATAN KONSEP SK',
                'icon' => 'loader',
                'class_color' => 'info',
            ),
            5 => array(
                'id_status' => 4,
                'nama_status' => 'VALIDASI KETUA ',
                'icon' => 'check-circle',
                'class_color' => 'success',
            ),
            6 => array(
                'id_status' => 5,
                'nama_status' => 'VERIFIKASI KABID',
                'icon' => 'check-circle',
                'class_color' => 'success',
            ),
            7 => array(
                'id_status' => 6,
                'nama_status' => 'VERIFIKASI SEKRETARIS',
                'icon' => 'check-circle',
                'class_color' => 'success',
            ),
            8 => array(
                'id_status' => 7,
                'nama_status' => 'VERIFIKASI KA BPKAD',
                'icon' => 'check-circle',
                'class_color' => 'success',
            ),
            9 => array(
                'id_status' => 8,
                'nama_status' => 'PENOMERAN SURAT',
                'icon' => 'loader',
                'class_color' => 'info',
            ),
            10 => array(
                'id_status' => 10,
                'nama_status' => 'PENGEMBALIAN DOKUMEN & CETAK SURAT',
                'icon' => 'check-circle',
                'class_color' => 'info',
            ),
            11 => array(
                'id_status' => 99,
                'nama_status' => 'REJECT',
                'icon' => 'alert',
                'class_color' => 'danger',
            ),

            12 => array(
                'id_status' => 100,
                'nama_status' => 'DATA BELUM LENGKAP',
                'icon' => 'alert',
                'class_color' => 'danger',
            ),
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_status');
        Schema::dropIfExists('m_status_ipt');
    }
};
