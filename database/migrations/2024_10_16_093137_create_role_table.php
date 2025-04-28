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
        Schema::create('role', function (Blueprint $table) {
            $table->integer('id_role')->primary();
            $table->string('nm_role', 60);
            $table->integer('id_creator');
            $table->timestamps();
            $table->softDeletes();
        });

        \DB::table('role')->delete();

        \DB::table('role')->insert(array(
            0 => array(
                'id_role' => 1,
                'nm_role' => 'Administrator',
                'id_creator' => '1',
            ),
            1 => array(
                'id_role' => 2,
                'nm_role' => 'Petugas P3BMD',
                'id_creator' => '1',
            ),
            2 => array(
                'id_role' => 3,
                'nm_role' => 'Petugas P2BMD',
                'id_creator' => '1',
            ),
            3 => array(
                'id_role' => 4,
                'nm_role' => 'Subkoor',
                'id_creator' => '1',
            ),
            4 => array(
                'id_role' => 5,
                'nm_role' => 'Kabid',
                'id_creator' => '1',
            ),
            6 => array(
                'id_role' => 6,
                'nm_role' => 'Sekretaris',
                'id_creator' => '1',
            ),
            7 => array(
                'id_role' => 7,
                'nm_role' => 'Kaban',
                'id_creator' => '1',
            ),
            8 => array(
                'id_role' => 8,
                'nm_role' => 'Arsip',
                'id_creator' => '1',
            ),
            9 => array(
                'id_role' => 9,
                'nm_role' => 'Petugas BAP',
                'id_creator' => '1',
            ),
            10 => array(
                'id_role' => 10,
                'nm_role' => 'Pembuatan SK',
                'id_creator' => '1',
            ),
            11 => array(
                'id_role' => 11,
                'nm_role' => 'Ketua Tim',
                'id_creator' => '1',
            ),
            12 => array(
                'id_role' => 99,
                'nm_role' => 'Pemohon',
                'id_creator' => '1',
            ),
            13 => array(
                'id_role' => 12,
                'nm_role' => 'Petugas Berkas',
                'id_creator' => '1',
            ),
            14 => array(
                'id_role' => 13,
                'nm_role' => 'Penyelia',
                'id_creator' => '1',
            ),
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role');
    }
};
