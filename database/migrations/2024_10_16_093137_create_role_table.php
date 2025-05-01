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
