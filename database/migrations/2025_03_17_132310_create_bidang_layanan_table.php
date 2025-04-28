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
        Schema::create('bidang_layanan', function (Blueprint $table) {
            $table->id();
            $table->string('bidang');
            $table->integer('id_layanan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        \DB::table('bidang_layanan')->delete();

        \DB::table('bidang_layanan')->insert(array(
            // P3BMD
            array(
                'bidang' => 'P3BMD',
                'id_layanan' => 1,
            ),
            array(
                'bidang' => 'P3BMD',
                'id_layanan' => 2,
            ),
            array(
                'bidang' => 'P3BMD',
                'id_layanan' => 3,
            ),
            array(
                'bidang' => 'P3BMD',
                'id_layanan' => 8,
            ),

            // P2BMD
            array(
                'bidang' => 'P2BMD',
                'id_layanan' => 6,
            ),
            array(
                'bidang' => 'P2BMD',
                'id_layanan' => 7,
            ),

            // SEKRETARIAT
            array(
                'bidang' => 'SEKRETARIAT',
                'id_layanan' => 1,
            ),
            array(
                'bidang' => 'SEKRETARIAT',
                'id_layanan' => 2,
            ),
            array(
                'bidang' => 'SEKRETARIAT',
                'id_layanan' => 3,
            ),
            array(
                'bidang' => 'SEKRETARIAT',
                'id_layanan' => 6,
            ),
            array(
                'bidang' => 'SEKRETARIAT',
                'id_layanan' => 7,
            ),
            array(
                'bidang' => 'SEKRETARIAT',
                'id_layanan' => 8,
            ),
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidang_layanan');
    }
};
