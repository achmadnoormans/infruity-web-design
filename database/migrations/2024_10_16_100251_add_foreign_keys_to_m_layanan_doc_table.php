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
        Schema::table('m_layanan_doc', function (Blueprint $table) {
            $table->foreign(['id_layanan'], 'layanan_doc_layanan')->references(['id_layanan'])->on('m_layanan')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_layanan_doc', function (Blueprint $table) {
            //
        });
    }
};
