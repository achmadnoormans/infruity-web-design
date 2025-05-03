<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id_user', true);
            $table->string('nickname', 10)->nullable();
            $table->string('nm_user', 100)->nullable();
            $table->string('username', 100)->nullable();
            $table->string('telp', 16)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_aktif')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        \DB::table('users')->delete();

        \DB::table('users')->insert(array(
            array(
                'nm_user' => 'MOCH IRSYADUL ANAM',
                'nickname' => 'irsyad',
                'username' => 'irsyad7798@gmail.com',
                'email' => 'irsyad7798@gmail.com',
                'password' => '$2y$10$wzqj/2.YIrD7bBbCb.ndhuoDktih2.bulhQQmpi6j5mXxQHdeFf.q',
                'telp' => '08674514312331',
            ),
            array(
                'nm_user' => 'Achmad Noorman Setiawan',
                'nickname' => 'noorman',
                'username' => 'noorman@infruity.com',
                'email' => 'noorman@infruity.com',
                'password' => Hash::make('noormangans'),
                'telp' => '08674514312331',
            ),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
