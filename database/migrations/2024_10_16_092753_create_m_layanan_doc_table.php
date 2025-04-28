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
        Schema::create('m_layanan_doc', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_layanan')->index('layanan_doc_layanan');
            $table->string('nama_document');
            $table->string('status')->nullable();
            $table->string('id_user', 32);
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        \DB::table('m_layanan_doc')->delete();

        \DB::table('m_layanan_doc')->insert(array(
            // Fotocopy IPT
            array(
                'id_layanan' => 1,
                'nama_document' => 'Fotocopy KTP',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 1,
                'nama_document' => 'Fotocopy KK',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 1,
                'nama_document' => 'Fotocopy legalisir Akta Pendirian (jika badan hukum)',
                'status' => null,
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 1,
                'nama_document' => 'Fotocopy dokumen kepemilikan jika IPT sudah beralih',
                'status' => null,
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 1,
                'nama_document' => 'Formulir',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => 'Diisi setelah input form ini',
            ),
            // Fotocopy Rekom Iklan Mandiri
            array(
                'id_layanan' => 2,
                'nama_document' => 'Fotocopy KTP',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 2,
                'nama_document' => 'Fotocopy KK',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 2,
                'nama_document' => 'Fotocopy Surat Keterangan Kehilangan dari Kepolisian',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 2,
                'nama_document' => 'Fotocopy dokumen kepemilikan jika IPT sudah beralih',
                'status' => null,
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 2,
                'nama_document' => 'Formulir',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => 'Diisi setelah input form ini',
            ),
            // Balik Nama Mandiri
            array(
                'id_layanan' => 3,
                'nama_document' => 'Fotocopy KTP',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 3,
                'nama_document' => 'Fotocopy KK',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 3,
                'nama_document' => 'Fotocopy legalisir Akta Pendirian (jika badan hukum)',
                'status' => null,
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 3,
                'nama_document' => 'Fotocopy SKRK',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 3,
                'nama_document' => 'Fotocopy IPT',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 3,
                'nama_document' => 'Fotocopy SSRD atau Tanda bukti lunas retribusi IPT',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 3,
                'nama_document' => 'Fotocopy dokumen peralihan',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 3,
                'nama_document' => 'Alasan peralihan IPT (kronologi)',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 3,
                'nama_document' => 'Pengumuman di Surat Kabar (Iklan)',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 3,
                'nama_document' => 'Formulir',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => 'Diisi setelah input form ini',
            ),

            // Fotocopy Rekom Iklan Kolektif
            array(
                'id_layanan' => 4,
                'nama_document' => 'Fotocopy KTP',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 4,
                'nama_document' => 'Fotocopy KK',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 4,
                'nama_document' => 'Fotocopy Surat Keterangan Kehilangan dari Kepolisian',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 4,
                'nama_document' => 'Fotocopy dokumen kepemilikan jika IPT sudah beralih',
                'status' => null,
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 4,
                'nama_document' => 'Formulir',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => 'Diisi setelah input form ini',
            ),

            // Balik Nama Kolektif
            array(
                'id_layanan' => 5,
                'nama_document' => 'Fotocopy KTP',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 5,
                'nama_document' => 'Fotocopy KK',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 5,
                'nama_document' => 'Fotocopy legalisir Akta Pendirian (jika badan hukum)',
                'status' => null,
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 5,
                'nama_document' => 'Fotocopy SKRK',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 5,
                'nama_document' => 'Fotocopy IPT',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 5,
                'nama_document' => 'Fotocopy SSRD atau Tanda bukti lunas retribusi IPT',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 5,
                'nama_document' => 'Fotocopy dokumen peralihan',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 5,
                'nama_document' => 'Alasan peralihan IPT (kronologi)',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 5,
                'nama_document' => 'Pengumuman di Surat Kabar (Iklan)',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 5,
                'nama_document' => 'Formulir',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => 'Diisi setelah input form ini',
            ),

            // Permohonan Status Tanah
            array(
                'id_layanan' => 7,
                'nama_document' => 'Fotocopy KTP',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 7,
                'nama_document' => 'Fotocopy KK',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 7,
                'nama_document' => 'Fotocopy PBB / SPPT tahun terakhir surat keterangan NJOP dari BPKPD',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 7,
                'nama_document' => 'Surat Pernyataan Kepemilikan Penguasaan Bangunan',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 7,
                'nama_document' => 'Surat Kuasa Apabila Perlu Dikuasakan',
                'status' => null,
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 7,
                'nama_document' => 'Formulir',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => 'Diisi setelah input form ini',
            ),

            // Pengurangan IPT
            array(
                'id_layanan' => 8,
                'nama_document' => 'Foto Persil',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
            array(
                'id_layanan' => 8,
                'nama_document' => 'Dokumen Pendukung',
                'status' => 'required',
                'id_user' => '1',
                'keterangan' => null,
            ),
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_layanan_doc');
    }
};
