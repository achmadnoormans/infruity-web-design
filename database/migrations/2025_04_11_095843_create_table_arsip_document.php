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
        Schema::create('arsip_document', function (Blueprint $table) {
            $table->id();
            $table->integer('arsip_id');
            $table->string('document_persyaratan')->nullable();
            $table->string('document_bap')->nullable();
            $table->string('document_surat')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_document');
    }
};
