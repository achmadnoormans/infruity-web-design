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
        Schema::create('t_permohonan_arsip', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_permohonan');
            $table->text('file')->nullable();
            $table->text('no_persil')->nullable();
            $table->text('nama_pemegang_ijin')->nullable();
            $table->string('alamat_persil')->nullable();
            $table->date('tanggal_ipt')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();            
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_permohonan_arsip');
    }
};
