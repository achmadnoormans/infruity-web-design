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
        Schema::create('ipt_pengurangan_bap', function (Blueprint $table) {
            $table->id();
            $table->integer('id_permohonan');
            $table->text('file')->nullable();
            $table->text('peruntukan')->nullable();
            $table->string('penggunaan')->nullable();
            $table->integer('luas')->nullable();
            $table->string('no_ipt')->nullable();
            $table->date('tanggal_ipt')->nullable();
            $table->string('no_skrd')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipt_pengurangan_bap');
    }
};
