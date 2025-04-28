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
        Schema::create('t_permohonan_surat', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_permohonan')->nullable();
            $table->text('isi')->nullable();
            $table->text('list_nama')->nullable();
            $table->string('nomer_surat')->nullable();
            $table->string('type_surat')->nullable();
            $table->date('tgl_surat')->nullable();
            $table->text('no_persil')->nullable();
            $table->date('tgl_ipt')->nullable();
            $table->string('nama_pemegang_ipt')->nullable();
            $table->string('alamat_persil')->nullable();
            $table->string('file')->nullable();
            $table->integer('is_verifikasi')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_permohonan_surat');
    }
};
