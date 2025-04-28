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
        Schema::create('t_permohonan_bap', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_permohonan');
            $table->text('file')->nullable();
            $table->text('peruntukan')->nullable();
            $table->string('penggunaan')->nullable();
            $table->integer('luas')->nullable();
            $table->string('no_ipt')->nullable();
            $table->date('tanggal_ipt')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // \DB::table('t_permohonan_bap')->delete();

        // \DB::table('t_permohonan_bap')->insert(array(
        //     16 => array(
        //         'id_permohonan' => 1,
        //         'file' => 'file_bap/8fANDF0ytk5R1UbJntP6ZDKhcbwI72M6GK7EhQjp.pdf',
        //         'peruntukan' => 'Buka Warung',
        //         'penggunaan' => 'Usaha',
        //         'luas' => 120,
        //         'no_ipt' => '1231231231231',
        //         'tanggal_ipt' => '2024-10-15',
        //         'created_at' => '2024-10-15 10:05:11',
        //         'updated_at' => '2024-10-15 10:05:11',
        //     ),
        //     17 => array(
        //         'id_permohonan' => 2,
        //         'file' => 'file_bap/y1yNkmw6xyOh6fvWrITxdX9gTEs1XhziFNiOFosO.pdf',
        //         'peruntukan' => 'Buka Warung',
        //         'penggunaan' => 'Usaha',
        //         'luas' => 120,
        //         'no_ipt' => '1231231231231',
        //         'tanggal_ipt' => '2024-10-15',
        //         'created_at' => '2024-10-15 10:26:56',
        //         'updated_at' => '2024-10-15 10:26:56',
        //     ),
        // ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_permohonan_bap');
    }
};
