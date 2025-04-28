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
        Schema::create('t_permohonan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('no_permohonan')->nullable();
            $table->integer('id_layanan')->nullable();
            $table->string('nama_pemohon')->nullable();
            $table->string('alamat_pemohon')->nullable();
            $table->string('telepon_pemohon')->nullable();
            $table->string('nama_pemegang_ipt')->nullable();
            $table->string('no_ipt')->nullable();
            $table->string('tanggal_ipt')->nullable();
            $table->string('alamat_persil')->nullable();
            $table->string('nomor_kehilangan_dari_kepolisian')->nullable();
            $table->string('pekerjaan_pemohon')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota')->nullable();
            $table->date('tanggal_pengajuan')->nullable();
            $table->integer('id_user');
            $table->integer('id_status');
            $table->integer('id_surat')->nullable();
            $table->integer('is_lengkap')->nullable();
            $table->string('jenis_iklan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // \DB::table('t_permohonan')->delete();

        // \DB::table('t_permohonan')->insert(array(
        //     array(
        //         'no_permohonan' => 'BPKAD/PIPT/241015/1336',
        //         'id_layanan' => 1,
        //         'nama_pemohon' => 'Irsyadul Anam',
        //         'alamat_pemohon' => 'Surabaya No.12',
        //         'telepon_pemohon' => '12312312312',
        //         'nama_pemegang_ipt' => 'Irsyad',
        //         'no_ipt' => '',
        //         'tanggal_ipt' => '',
        //         'alamat_persil' => 'Ketabang Surabaya',
        //         'nomor_kehilangan_dari_kepolisian' => '',
        //         'tanggal_pengajuan' => '2024-10-15',
        //         'created_at' => '2024-10-15 10:18:39',
        //         'updated_at' => '2024-10-15 10:18:39',
        //         'id_user' => 6,
        //         'id_status' => 1,
        //     ),
        // ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_permohonan');
    }
};
