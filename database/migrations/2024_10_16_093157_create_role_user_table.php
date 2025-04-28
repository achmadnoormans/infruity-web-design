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
        Schema::create('role_user', function (Blueprint $table) {
            $table->integer('id_ru', true);
            $table->integer('id_user');
            $table->integer('id_role');
            $table->timestamps();
            $table->softDeletes();
        });

        \DB::table('role_user')->delete();

        \DB::table('role_user')->insert(array(
            array(
                'id_user' => 1,
                'id_role' => 1,
            ),
            array(
                'id_user' => 2,
                'id_role' => 99,
            ),
            array(
                'id_user' => 3,
                'id_role' => 2,
            ),
            array(
                'id_user' => 4,
                'id_role' => 3,
            ),
            array(
                'id_user' => 5,
                'id_role' => 4,
            ),
            array(
                'id_user' => 6,
                'id_role' => 5,
            ),
            array(
                'id_user' => 7,
                'id_role' => 6,
            ),
            array(
                'id_user' => 8,
                'id_role' => 7,
            ),
            array(
                'id_user' => 9,
                'id_role' => 8,
            ),
            array(
                'id_user' => 10,
                'id_role' => 9,
            ),
            array(
                'id_user' => 11,
                'id_role' => 10,
            ),
            array(
                'id_user' => 12,
                'id_role' => 11,
            ),
            array(
                'id_user' => 13,
                'id_role' => 12,
            ),

            // User yang didafar oleh admin
            array(
                'id_user' => 14,
                'id_role' => 12,
            ),
            array(
                'id_user' => 15,
                'id_role' => 8,
            ),
            array(
                'id_user' => 16,
                'id_role' => 9,
            ),
            array(
                'id_user' => 17,
                'id_role' => 9,
            ),
            array(
                'id_user' => 18,
                'id_role' => 10,
            ),
            array(
                'id_user' => 19,
                'id_role' => 11,
            ),
            array(
                'id_user' => 20,
                'id_role' => 5,
            ),
            array(
                'id_user' => 21,
                'id_role' => 6,
            ),
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};
