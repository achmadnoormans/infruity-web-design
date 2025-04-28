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
        Schema::create('ipt_pengurangan', function (Blueprint $table) {
            $table->id();
            $table->string('no_permohonan')->nullable();
            $table->string('type')->nullable();
            $table->string('no_sk')->nullable();
            $table->bigInteger('nik')->nullable();
            $table->string('nama_pemohon')->nullable();
            $table->string('telepon_pemohon')->nullable();
            $table->string('alamat_persil')->nullable();
            $table->string(column: 'penggunaan')->nullable();
            $table->date('tanggal_pengajuan')->nullable();
            $table->integer('id_user');
            $table->integer('id_status');
            $table->integer('id_surat')->nullable();
            $table->integer('is_lengkap')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipt_pengurangan');
    }
};
