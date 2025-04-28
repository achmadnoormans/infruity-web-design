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
        Schema::create('ipt_pengurangan_surat', function (Blueprint $table) {
            $table->id();
            $table->integer('id_permohonan')->nullable();
            $table->text('isi')->nullable();
            $table->text('list_nama')->nullable();
            $table->string('nomer_surat')->nullable();
            $table->string('type_surat')->nullable();
            $table->date('tgl_surat')->nullable();
            $table->text('no_persil')->nullable();
            $table->date('tgl_ipt')->nullable();
            $table->text('nama_pemegang_ipt')->nullable();
            $table->string('alamat_persil')->nullable();
            $table->string('nominal_pengurangan')->nullable();
            $table->text('bukti')->nullable();
            $table->string('periode')->nullable();
            $table->string('no_skrd')->nullable();
            $table->string('tgl_skrd')->nullable();
            $table->string('file')->nullable();
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
        Schema::dropIfExists('ipt_pengurangan_surat');
    }
};
