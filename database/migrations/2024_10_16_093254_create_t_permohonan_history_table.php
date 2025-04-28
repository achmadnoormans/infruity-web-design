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
        Schema::create('t_permohonan_history', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_permohonan');
            $table->string('type')->nullable();
            $table->integer('id_status');
            $table->date('tgl_status')->nullable();
            $table->string('nm_status')->nullable();
            $table->string('id_verifikator')->nullable();
            $table->string('nama_verifikator')->nullable();
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
        Schema::dropIfExists('t_permohonan_history');
    }
};
