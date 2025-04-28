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
        Schema::create('t_permohonan_document', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_permohonan');
            $table->string('file')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ipt_pengurangan_document', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_permohonan');
            $table->string('file')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_permohonan_document');
        Schema::dropIfExists('ipt_pengurangan_document');
    }
};
