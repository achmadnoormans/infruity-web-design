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
        Schema::create('m_layanan_form', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_layanan')->index('layanan_doc_layanan');
            $table->string('nama_form');
            $table->string('type');
            $table->string('id_user', 32);
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        \DB::table('m_layanan_form')->delete();

        \DB::table('m_layanan_form')->insert(array(
            // Fotocopy IPT
            array(
                'id_layanan' => 1,
                'nama_form' => 'Nama Pemohon',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 1,
                'nama_form' => 'Alamat Pemohon',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 1,
                'nama_form' => 'Telepon Pemohon',
                'type' => 'number',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 1,
                'nama_form' => 'Nama Pemegang IPT',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 1,
                'nama_form' => 'Alamat Persil',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),

            // Rekom Iklan Mandiri
            array(
                'id_layanan' => 2,
                'nama_form' => 'Nama Pemohon',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 2,
                'nama_form' => 'Alamat Pemohon',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 2,
                'nama_form' => 'Telepon Pemohon',
                'type' => 'number',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 2,
                'nama_form' => 'Nomor Kehilangan dari Kepolisian',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 2,
                'nama_form' => 'Nama Pemegang IPT',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 2,
                'nama_form' => 'Alamat Persil',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),

            // Balik Nama Mandiri
            array(
                'id_layanan' => 3,
                'nama_form' => 'Nama Pemohon',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 3,
                'nama_form' => 'Alamat Pemohon',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 3,
                'nama_form' => 'Telepon Pemohon',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 3,
                'nama_form' => 'No. IPT',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 3,
                'nama_form' => 'Tanggal IPT',
                'type' => 'date',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 3,
                'nama_form' => 'Alamat Persil',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),

            // Rekom Iklan Kolektif
            array(
                'id_layanan' => 4,
                'nama_form' => 'Nama Pemohon',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 4,
                'nama_form' => 'Alamat Pemohon',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 4,
                'nama_form' => 'Telepon Pemohon',
                'type' => 'number',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 4,
                'nama_form' => 'Nomor Kehilangan dari Kepolisian',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 4,
                'nama_form' => 'Nama Pemegang IPT',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 4,
                'nama_form' => 'Alamat Persil',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),

            // Balik Nama Kolektif
            array(
                'id_layanan' => 5,
                'nama_form' => 'Nama Pemohon',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 5,
                'nama_form' => 'Alamat Pemohon',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 5,
                'nama_form' => 'Telepon Pemohon',
                'type' => 'number',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 5,
                'nama_form' => 'Nomor Kehilangan dari Kepolisian',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 5,
                'nama_form' => 'Nama Pemegang IPT',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 5,
                'nama_form' => 'Alamat Persil',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),


            // Permohonan Status Tanah
            array(
                'id_layanan' => 7,
                'nama_form' => 'Nama Pemohon',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 7,
                'nama_form' => 'Alamat Pemohon',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 7,
                'nama_form' => 'Pekerjaan Pemohon',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 7,
                'nama_form' => 'Telepon Pemohon',
                'type' => 'number',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 7,
                'nama_form' => 'Alamat Persil',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 7,
                'nama_form' => 'Kelurahan',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 7,
                'nama_form' => 'Kecamatan',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 7,
                'nama_form' => 'Kota',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),

            // Permohonan Pengurangan IPT
            array(
                'id_layanan' => 8,
                'nama_form' => 'NIK',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 8,
                'nama_form' => 'Nama Pemohon',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 8,
                'nama_form' => 'Alamat Persil',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 8,
                'nama_form' => 'No SK',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
            array(
                'id_layanan' => 8,
                'nama_form' => 'Penggunaan',
                'type' => 'text',
                'id_user' => '1',
                'status' => 'required',
            ),
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_layanan_form');
    }
};
