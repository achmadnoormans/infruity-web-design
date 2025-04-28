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
        Schema::create('role_menu', function (Blueprint $table) {
            $table->integer('id_rm', true);
            $table->integer('id_role')->index('role');
            $table->string('permission')->index('menu');
            $table->timestamps();
            $table->softDeletes();
        });

        \DB::table('role_menu')->delete();

        \DB::table('role_menu')->insert(array(
            array(
                'id_role' => 1,
                'permission' => 'show-dashboard',
            ),
            array(
                'id_role' => 1,
                'permission' => 'show-permohonan',
            ),
            array(
                'id_role' => 1,
                'permission' => 'show-master',
            ),
            array(
                'id_role' => 1,
                'permission' => 'show-permohonan',
            ),
            array(
                'id_role' => 1,
                'permission' => 'show-change-password',
            ),
            array(
                'id_role' => 1,
                'permission' => 'show-bap',
            ),
            array(
                'id_role' => 1,
                'permission' => 'upload-file',
            ),
            array(
                'id_role' => 1,
                'permission' => 'verifikasi',
            ),
            array(
                'id_role' => 1,
                'permission' => 'do-verifikasi',
            ),
            array(
                'id_role' => 1,
                'permission' => 'upload-bap',
            ),
            array(
                'id_role' => 1,
                'permission' => 'create-surat',
            ),
            array(
                'id_role' => 1,
                'permission' => 'show-surat',
            ),
            array(
                'id_role' => 1,
                'permission' => 'verifikasi-surat',
            ),
            array(
                'id_role' => 1,
                'permission' => 'verifikasi-kaban',
            ),
            array(
                'id_role' => 1,
                'permission' => 'selesaikan-proses',
            ),
            array(
                'id_role' => 1,
                'permission' => 'cetak-permohonan',
            ),
            array(
                'id_role' => 1,
                'permission' => 'cetak-formulir',
            ),
            array(
                'id_role' => 1,
                'permission' => 'cetak-surat',
            ),
            array(
                'id_role' => 1,
                'permission' => 'cetak-bap',
            ),

            // Pemohon
            array(
                'id_role' => 99,
                'permission' => 'show-dashboard',
            ),
            array(
                'id_role' => 99,
                'permission' => 'show-change-password',
            ),
            array(
                'id_role' => 99,
                'permission' => 'show-permohonan',
            ),
            array(
                'id_role' => 99,
                'permission' => 'show-ipt-pengurangan',
            ),
            array(
                'id_role' => 99,
                'permission' => 'show-list-permohonan',
            ),
            array(
                'id_role' => 99,
                'permission' => 'create',
            ),
            array(
                'id_role' => 99,
                'permission' => 'cetak-formulir',
            ),
            array(
                'id_role' => 99,
                'permission' => 'upload-file',
            ),
            array(
                'id_role' => 99,
                'permission' => 'cetak-permohonan',
            ),
            array(
                'id_role' => 99,
                'permission' => 'show-document',
            ),
            array(
                'id_role' => 99,
                'permission' => 'history',
            ),
            array(
                'id_role' => 99,
                'permission' => 'submit-data',
            ),
            array(
                'id_role' => 99,
                'permission' => 'submit-ulang',
            ),
            array(
                'id_role' => 99,
                'permission' => 'cetak-surat',
            ),
            array(
                'id_role' => 99,
                'permission' => 'permohonan-edit',
            ),

            // P3BMD
            array(
                'id_role' => 2,
                'permission' => 'show-permohonan',
            ),
            array(
                'id_role' => 2,
                'permission' => 'show-dashboard',
            ),
            array(
                'id_role' => 2,
                'permission' => 'show-change-password',
            ),
            array(
                'id_role' => 2,
                'permission' => 'show-monitoring',
            ),
            array(
                'id_role' => 2,
                'permission' => 'show-bap',
            ),
            array(
                'id_role' => 2,
                'permission' => 'upload-file',
            ),
            array(
                'id_role' => 2,
                'permission' => 'verifikasi',
            ),
            array(
                'id_role' => 2,
                'permission' => 'do-verifikasi',
            ),
            array(
                'id_role' => 2,
                'permission' => 'upload-bap',
            ),
            array(
                'id_role' => 2,
                'permission' => 'create-surat',
            ),
            array(
                'id_role' => 2,
                'permission' => 'show-surat',
            ),
            array(
                'id_role' => 2,
                'permission' => 'cetak-surat',
            ),
            array(
                'id_role' => 2,
                'permission' => 'cetak-bap',
            ),
            array(
                'id_role' => 2,
                'permission' => 'show-permohonan-proses-selesai',
            ),
            array(
                'id_role' => 2,
                'permission' => 'show-keterangan-arsip',
            ),
            array(
                'id_role' => 2,
                'permission' => 'verifikasi-surat',
            ),
            array(
                'id_role' => 2,
                'permission' => 'show-document',
            ),
            array(
                'id_role' => 2,
                'permission' => 'show-permohonan-selesai',
            ),
            array(
                'id_role' => 2,
                'permission' => 'show-permohonan-proses',
            ),
            array(
                'id_role' => 2,
                'permission' => 'edit',
            ),
            array(
                'id_role' => 2,
                'permission' => 'selesaikan-proses',
            ),
            array(
                'id_role' => 2,
                'permission' => 'history',
            ),

            // P2BMD
            array(
                'id_role' => 3,
                'permission' => 'show-permohonan',
            ),
            array(
                'id_role' => 3,
                'permission' => 'show-dashboard',
            ),
            array(
                'id_role' => 3,
                'permission' => 'show-change-password',
            ),
            array(
                'id_role' => 3,
                'permission' => 'show-monitoring',
            ),
            array(
                'id_role' => 3,
                'permission' => 'show-bap',
            ),
            array(
                'id_role' => 3,
                'permission' => 'upload-file',
            ),
            array(
                'id_role' => 3,
                'permission' => 'verifikasi',
            ),
            array(
                'id_role' => 3,
                'permission' => 'do-verifikasi',
            ),
            array(
                'id_role' => 3,
                'permission' => 'upload-bap',
            ),
            array(
                'id_role' => 3,
                'permission' => 'create-surat',
            ),
            array(
                'id_role' => 3,
                'permission' => 'show-surat',
            ),
            array(
                'id_role' => 3,
                'permission' => 'cetak-surat',
            ),
            array(
                'id_role' => 3,
                'permission' => 'cetak-bap',
            ),
            array(
                'id_role' => 3,
                'permission' => 'show-permohonan-bap',
            ),
            array(
                'id_role' => 3,
                'permission' => 'show-permohonan-konsep-surat',
            ),
            array(
                'id_role' => 3,
                'permission' => 'show-keterangan-arsip',
            ),
            array(
                'id_role' => 3,
                'permission' => 'verifikasi-surat',
            ),
            array(
                'id_role' => 3,
                'permission' => 'show-document',
            ),
            array(
                'id_role' => 3,
                'permission' => 'show-permohonan-selesai',
            ),
            array(
                'id_role' => 3,
                'permission' => 'show-permohonan-proses',
            ),
            array(
                'id_role' => 3,
                'permission' => 'edit',
            ),
            array(
                'id_role' => 3,
                'permission' => 'history',
            ),

            // Kabid
            array(
                'id_role' => 5,
                'permission' => 'show-permohonan',
            ),
            array(
                'id_role' => 5,
                'permission' => 'show-ipt-pengurangan',
            ),
            array(
                'id_role' => 5,
                'permission' => 'show-dashboard',
            ),
            array(
                'id_role' => 5,
                'permission' => 'show-change-password',
            ),
            array(
                'id_role' => 5,
                'permission' => 'show-monitoring',
            ),
            array(
                'id_role' => 5,
                'permission' => 'show-bap',
            ),
            array(
                'id_role' => 5,
                'permission' => 'upload-file',
            ),
            array(
                'id_role' => 5,
                'permission' => 'verifikasi',
            ),
            array(
                'id_role' => 5,
                'permission' => 'do-verifikasi',
            ),
            array(
                'id_role' => 5,
                'permission' => 'upload-bap',
            ),
            array(
                'id_role' => 5,
                'permission' => 'create-surat',
            ),
            array(
                'id_role' => 5,
                'permission' => 'show-surat',
            ),
            array(
                'id_role' => 5,
                'permission' => 'show-surat-keterangan',
            ),
            array(
                'id_role' => 5,
                'permission' => 'cetak-surat',
            ),
            array(
                'id_role' => 5,
                'permission' => 'cetak-bap',
            ),
            array(
                'id_role' => 5,
                'permission' => 'show-permohonan-verifikasi-kabid',
            ),
            array(
                'id_role' => 5,
                'permission' => 'show-keterangan-arsip',
            ),
            array(
                'id_role' => 5,
                'permission' => 'verifikasi-kabid',
            ),
            array(
                'id_role' => 5,
                'permission' => 'show-document',
            ),
            array(
                'id_role' => 5,
                'permission' => 'show-permohonan-selesai',
            ),
            array(
                'id_role' => 5,
                'permission' => 'show-permohonan-proses',
            ),
            array(
                'id_role' => 5,
                'permission' => 'edit',
            ),
            array(
                'id_role' => 5,
                'permission' => 'history',
            ),


            // SUBKOOR
            array(
                'id_role' => 4,
                'permission' => 'show-permohonan',
            ),
            array(
                'id_role' => 4,
                'permission' => 'show-ipt-pengurangan',
            ),
            array(
                'id_role' => 4,
                'permission' => 'show-dashboard',
            ),
            array(
                'id_role' => 4,
                'permission' => 'show-change-password',
            ),
            array(
                'id_role' => 4,
                'permission' => 'show-monitoring',
            ),
            array(
                'id_role' => 4,
                'permission' => 'show-bap',
            ),
            array(
                'id_role' => 4,
                'permission' => 'upload-file',
            ),
            array(
                'id_role' => 4,
                'permission' => 'verifikasi',
            ),
            array(
                'id_role' => 4,
                'permission' => 'do-verifikasi',
            ),
            array(
                'id_role' => 4,
                'permission' => 'upload-bap',
            ),
            array(
                'id_role' => 4,
                'permission' => 'create-surat',
            ),
            array(
                'id_role' => 4,
                'permission' => 'show-surat',
            ),
            array(
                'id_role' => 4,
                'permission' => 'cetak-surat',
            ),
            array(
                'id_role' => 4,
                'permission' => 'cetak-bap',
            ),
            array(
                'id_role' => 4,
                'permission' => 'show-permohonan-bap',
            ),
            array(
                'id_role' => 4,
                'permission' => 'show-permohonan-konsep-surat',
            ),
            array(
                'id_role' => 4,
                'permission' => 'show-keterangan-arsip',
            ),
            array(
                'id_role' => 4,
                'permission' => 'verifikasi-surat',
            ),
            array(
                'id_role' => 4,
                'permission' => 'show-document',
            ),
            array(
                'id_role' => 4,
                'permission' => 'show-permohonan-selesai',
            ),
            array(
                'id_role' => 4,
                'permission' => 'show-permohonan-proses',
            ),
            array(
                'id_role' => 4,
                'permission' => 'history',
            ),


            // SEKRETARIS
            array(
                'id_role' => 6,
                'permission' => 'show-dashboard',
            ),
            array(
                'id_role' => 6,
                'permission' => 'show-ipt-pengurangan',
            ),
            array(
                'id_role' => 6,
                'permission' => 'show-permohonan',
            ),
            array(
                'id_role' => 6,
                'permission' => 'show-change-password',
            ),
            array(
                'id_role' => 6,
                'permission' => 'show-monitoring',
            ),
            array(
                'id_role' => 6,
                'permission' => 'verifikasi',
            ),
            array(
                'id_role' => 6,
                'permission' => 'do-verifikasi',
            ),
            array(
                'id_role' => 6,
                'permission' => 'show-keterangan-arsip',
            ),
            array(
                'id_role' => 6,
                'permission' => 'verifikasi-sekretaris',
            ),
            array(
                'id_role' => 6,
                'permission' => 'cetak-surat',
            ),
            array(
                'id_role' => 6,
                'permission' => 'show-permohonan-verifikasi-sekretaris',
            ),
            array(
                'id_role' => 6,
                'permission' => 'show-bap',
            ),
            array(
                'id_role' => 6,
                'permission' => 'show-surat',
            ),
            array(
                'id_role' => 6,
                'permission' => 'show-document',
            ),
            array(
                'id_role' => 6,
                'permission' => 'show-permohonan-selesai',
            ),
            array(
                'id_role' => 6,
                'permission' => 'show-permohonan-proses',
            ),
            array(
                'id_role' => 6,
                'permission' => 'history',
            ),

            // KABAN
            array(
                'id_role' => 7,
                'permission' => 'show-dashboard',
            ),
            array(
                'id_role' => 7,
                'permission' => 'show-ipt-pengurangan',
            ),
            array(
                'id_role' => 7,
                'permission' => 'show-permohonan',
            ),
            array(
                'id_role' => 7,
                'permission' => 'show-change-password',
            ),
            array(
                'id_role' => 7,
                'permission' => 'show-monitoring',
            ),
            array(
                'id_role' => 7,
                'permission' => 'verifikasi',
            ),
            array(
                'id_role' => 7,
                'permission' => 'do-verifikasi',
            ),
            array(
                'id_role' => 7,
                'permission' => 'show-keterangan-arsip',
            ),
            array(
                'id_role' => 7,
                'permission' => 'cetak-surat',
            ),
            array(
                'id_role' => 7,
                'permission' => 'verifikasi-kaban',
            ),
            array(
                'id_role' => 7,
                'permission' => 'show-permohonan-verifikasi-kaban',
            ),
            array(
                'id_role' => 7,
                'permission' => 'show-bap',
            ),
            array(
                'id_role' => 7,
                'permission' => 'show-surat',
            ),
            array(
                'id_role' => 7,
                'permission' => 'show-document',
            ),
            array(
                'id_role' => 7,
                'permission' => 'show-permohonan-selesai',
            ),
            array(
                'id_role' => 7,
                'permission' => 'show-permohonan-proses',
            ),
            array(
                'id_role' => 7,
                'permission' => 'history',
            ),

            // ARSIP
            array(
                'id_role' => 8,
                'permission' => 'show-dashboard',
            ),
            array(
                'id_role' => 8,
                'permission' => 'show-permohonan',
            ),
            array(
                'id_role' => 8,
                'permission' => 'show-permohonan',
            ),
            array(
                'id_role' => 8,
                'permission' => 'show-change-password',
            ),
            array(
                'id_role' => 8,
                'permission' => 'show-monitoring',
            ),
            array(
                'id_role' => 8,
                'permission' => 'verifikasi-arsip',
            ),
            array(
                'id_role' => 8,
                'permission' => 'verifikasi',
            ),
            array(
                'id_role' => 8,
                'permission' => 'do-verifikasi',
            ),
            array(
                'id_role' => 8,
                'permission' => 'show-permohonan-submit',
            ),
            array(
                'id_role' => 8,
                'permission' => 'show-keterangan-arsip',
            ),
            array(
                'id_role' => 8,
                'permission' => 'show-document',
            ),
            array(
                'id_role' => 8,
                'permission' => 'show-permohonan-selesai',
            ),
            array(
                'id_role' => 8,
                'permission' => 'show-permohonan-proses',
            ),
            array(
                'id_role' => 8,
                'permission' => 'verifikasi-berkas',
            ),
            array(
                'id_role' => 8,
                'permission' => 'history',
            ),
            array(
                'id_role' => 8,
                'permission' => 'selesaikan-proses',
            ),

            // PETUGAS BERKAS
            array(
                'id_role' => 12,
                'permission' => 'show-dashboard',
            ),
            array(
                'id_role' => 12,
                'permission' => 'show-permohonan',
            ),
            array(
                'id_role' => 12,
                'permission' => 'show-permohonan',
            ),
            array(
                'id_role' => 12,
                'permission' => 'show-change-password',
            ),
            array(
                'id_role' => 12,
                'permission' => 'show-monitoring',
            ),
            array(
                'id_role' => 12,
                'permission' => 'verifikasi-berkas',
            ),
            array(
                'id_role' => 12,
                'permission' => 'verifikasi',
            ),
            array(
                'id_role' => 12,
                'permission' => 'do-verifikasi',
            ),
            array(
                'id_role' => 12,
                'permission' => 'show-permohonan-submit',
            ),
            array(
                'id_role' => 12,
                'permission' => 'show-keterangan-arsip',
            ),
            array(
                'id_role' => 12,
                'permission' => 'show-document',
            ),
            array(
                'id_role' => 12,
                'permission' => 'show-permohonan-selesai',
            ),
            array(
                'id_role' => 12,
                'permission' => 'show-permohonan-proses',
            ),
            array(
                'id_role' => 12,
                'permission' => 'history',
            ),

            // Petugas BAP
            array(
                'id_role' => 9,
                'permission' => 'show-permohonan',
            ),
            array(
                'id_role' => 9,
                'permission' => 'show-ipt-pengurangan',
            ),
            array(
                'id_role' => 9,
                'permission' => 'show-dashboard',
            ),
            array(
                'id_role' => 9,
                'permission' => 'show-change-password',
            ),
            array(
                'id_role' => 9,
                'permission' => 'show-monitoring',
            ),
            array(
                'id_role' => 9,
                'permission' => 'show-bap',
            ),
            array(
                'id_role' => 9,
                'permission' => 'upload-file',
            ),
            array(
                'id_role' => 9,
                'permission' => 'verifikasi',
            ),
            array(
                'id_role' => 9,
                'permission' => 'do-verifikasi',
            ),
            array(
                'id_role' => 9,
                'permission' => 'upload-bap',
            ),
            array(
                'id_role' => 9,
                'permission' => 'cetak-bap',
            ),
            array(
                'id_role' => 9,
                'permission' => 'show-permohonan-bap',
            ),
            array(
                'id_role' => 9,
                'permission' => 'show-keterangan-arsip',
            ),
            array(
                'id_role' => 9,
                'permission' => 'show-document',
            ),
            array(
                'id_role' => 9,
                'permission' => 'show-permohonan-selesai',
            ),
            array(
                'id_role' => 9,
                'permission' => 'show-permohonan-proses',
            ),
            array(
                'id_role' => 9,
                'permission' => 'edit',
            ),
            array(
                'id_role' => 9,
                'permission' => 'history',
            ),
            array(
                'id_role' => 9,
                'permission' => 'create-surat',
            ),
            array(
                'id_role' => 9,
                'permission' => 'show-surat',
            ),
            array(
                'id_role' => 9,
                'permission' => 'show-surat-keterangan',
            ),
            array(
                'id_role' => 9,
                'permission' => 'cetak-surat',
            ),
            array(
                'id_role' => 9,
                'permission' => 'surat-edit',
            ),

            // Pembuat SK
            array(
                'id_role' => 10,
                'permission' => 'show-permohonan',
            ),
            array(
                'id_role' => 10,
                'permission' => 'show-ipt-pengurangan',
            ),
            array(
                'id_role' => 10,
                'permission' => 'show-dashboard',
            ),
            array(
                'id_role' => 10,
                'permission' => 'show-change-password',
            ),
            array(
                'id_role' => 10,
                'permission' => 'show-monitoring',
            ),
            array(
                'id_role' => 10,
                'permission' => 'show-bap',
            ),
            array(
                'id_role' => 10,
                'permission' => 'upload-file',
            ),
            array(
                'id_role' => 10,
                'permission' => 'verifikasi',
            ),
            array(
                'id_role' => 10,
                'permission' => 'do-verifikasi',
            ),
            array(
                'id_role' => 10,
                'permission' => 'create-surat',
            ),
            array(
                'id_role' => 10,
                'permission' => 'show-surat',
            ),
            array(
                'id_role' => 10,
                'permission' => 'show-surat-keterangan',
            ),
            array(
                'id_role' => 10,
                'permission' => 'cetak-surat',
            ),
            array(
                'id_role' => 10,
                'permission' => 'cetak-bap',
            ),
            array(
                'id_role' => 10,
                'permission' => 'show-permohonan-bap',
            ),
            array(
                'id_role' => 10,
                'permission' => 'show-permohonan-konsep-surat',
            ),
            array(
                'id_role' => 10,
                'permission' => 'show-keterangan-arsip',
            ),
            array(
                'id_role' => 10,
                'permission' => 'show-document',
            ),
            array(
                'id_role' => 10,
                'permission' => 'show-permohonan-selesai',
            ),
            array(
                'id_role' => 10,
                'permission' => 'show-permohonan-proses',
            ),
            array(
                'id_role' => 10,
                'permission' => 'edit',
            ),
            array(
                'id_role' => 10,
                'permission' => 'history',
            ),
            array(
                'id_role' => 10,
                'permission' => 'create-surat-kolektif',
            ),
            array(
                'id_role' => 10,
                'permission' => 'save-surat-kolektif',
            ),
            array(
                'id_role' => 10,
                'permission' => 'edit-surat-kolektif',
            ),
            array(
                'id_role' => 10,
                'permission' => 'save-list',
            ),
            array(
                'id_role' => 10,
                'permission' => 'surat-edit',
            ),

            // Ketua TIM
            array(
                'id_role' => 11,
                'permission' => 'show-permohonan',
            ),
            array(
                'id_role' => 11,
                'permission' => 'show-ipt-pengurangan',
            ),
            array(
                'id_role' => 11,
                'permission' => 'show-dashboard',
            ),
            array(
                'id_role' => 11,
                'permission' => 'show-change-password',
            ),
            array(
                'id_role' => 11,
                'permission' => 'show-monitoring',
            ),
            array(
                'id_role' => 11,
                'permission' => 'show-bap',
            ),
            array(
                'id_role' => 11,
                'permission' => 'upload-file',
            ),
            array(
                'id_role' => 11,
                'permission' => 'verifikasi',
            ),
            array(
                'id_role' => 11,
                'permission' => 'do-verifikasi',
            ),
            array(
                'id_role' => 11,
                'permission' => 'show-surat',
            ),
            array(
                'id_role' => 11,
                'permission' => 'show-surat-keterangan',
            ),
            array(
                'id_role' => 11,
                'permission' => 'cetak-surat',
            ),
            array(
                'id_role' => 11,
                'permission' => 'cetak-bap',
            ),
            array(
                'id_role' => 11,
                'permission' => 'show-permohonan-bap',
            ),
            array(
                'id_role' => 11,
                'permission' => 'show-permohonan-konsep-surat',
            ),
            array(
                'id_role' => 11,
                'permission' => 'show-keterangan-arsip',
            ),
            array(
                'id_role' => 11,
                'permission' => 'verifikasi-surat',
            ),
            array(
                'id_role' => 11,
                'permission' => 'show-document',
            ),
            array(
                'id_role' => 11,
                'permission' => 'show-permohonan-selesai',
            ),
            array(
                'id_role' => 11,
                'permission' => 'show-permohonan-proses',
            ),
            array(
                'id_role' => 11,
                'permission' => 'edit',
            ),
            array(
                'id_role' => 11,
                'permission' => 'history',
            ),
            array(
                'id_role' => 11,
                'permission' => 'show-permohonan-verifikasi-ketua',
            ),

            // Ketua TIM
            array(
                'id_role' => 13,
                'permission' => 'show-permohonan',
            ),
            array(
                'id_role' => 13,
                'permission' => 'show-ipt-pengurangan',
            ),
            array(
                'id_role' => 13,
                'permission' => 'show-dashboard',
            ),
            array(
                'id_role' => 13,
                'permission' => 'show-change-password',
            ),
            array(
                'id_role' => 13,
                'permission' => 'show-monitoring',
            ),
            array(
                'id_role' => 13,
                'permission' => 'show-bap',
            ),
            array(
                'id_role' => 13,
                'permission' => 'upload-file',
            ),
            array(
                'id_role' => 13,
                'permission' => 'verifikasi',
            ),
            array(
                'id_role' => 13,
                'permission' => 'do-verifikasi',
            ),
            array(
                'id_role' => 13,
                'permission' => 'show-surat',
            ),
            array(
                'id_role' => 13,
                'permission' => 'show-surat-keterangan',
            ),
            array(
                'id_role' => 13,
                'permission' => 'cetak-surat',
            ),
            array(
                'id_role' => 13,
                'permission' => 'cetak-bap',
            ),
            array(
                'id_role' => 13,
                'permission' => 'show-permohonan-bap',
            ),
            array(
                'id_role' => 13,
                'permission' => 'show-permohonan-konsep-surat',
            ),
            array(
                'id_role' => 13,
                'permission' => 'show-keterangan-arsip',
            ),
            array(
                'id_role' => 13,
                'permission' => 'verifikasi-surat',
            ),
            array(
                'id_role' => 13,
                'permission' => 'show-document',
            ),
            array(
                'id_role' => 13,
                'permission' => 'show-permohonan-selesai',
            ),
            array(
                'id_role' => 13,
                'permission' => 'show-permohonan-proses',
            ),
            array(
                'id_role' => 13,
                'permission' => 'edit',
            ),
            array(
                'id_role' => 13,
                'permission' => 'history',
            ),
            array(
                'id_role' => 13,
                'permission' => 'show-penyelia-surat',
            ),
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_menu');
    }
};
