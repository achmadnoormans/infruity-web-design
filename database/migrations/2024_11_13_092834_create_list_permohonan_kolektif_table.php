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
        Schema::create('t_permohonan_kolektif_list', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_permohonan');
            $table->boolean('is_surat');
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
        Schema::dropIfExists('list_permohonan_kolektif');
    }
};
