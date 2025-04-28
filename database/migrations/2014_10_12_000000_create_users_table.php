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
                'username' => 'irsyad7798@gmail.com',
                'email' => 'irsyad7798@gmail.com',
                'password' => '$2y$10$wzqj/2.YIrD7bBbCb.ndhuoDktih2.bulhQQmpi6j5mXxQHdeFf.q',
                'telp' => '08674514312331',
            ),
            array(
                'nm_user' => 'pemohon',
                'username' => 'pemohon@gmail.com',
                'email' => 'pemohon@gmail.com',
                'password' => '$2y$10$.ROlm4QAWfHncLuuV6Kc1.hT6xuOfVSX0HUxZsMtSTu.QmeJ0oaF.',
                'telp' => null,
            ),
            array(
                'nm_user' => 'p3bmd',
                'username' => 'p3bmd@gmail.com',
                'email' => 'p3bmd@gmail.com',
                'password' => '$2y$10$.ROlm4QAWfHncLuuV6Kc1.hT6xuOfVSX0HUxZsMtSTu.QmeJ0oaF.',
                'telp' => null,
            ),
            array(
                'nm_user' => 'p2bmd',
                'username' => 'p2bmd@gmail.com',
                'email' => 'p2bmd@gmail.com',
                'password' => '$2y$10$.ROlm4QAWfHncLuuV6Kc1.hT6xuOfVSX0HUxZsMtSTu.QmeJ0oaF.',
                'telp' => null,
            ),
            array(
                'nm_user' => 'subkoor',
                'username' => 'subkoor@gmail.com',
                'email' => 'subkoor@gmail.com',
                'password' => '$2y$10$.ROlm4QAWfHncLuuV6Kc1.hT6xuOfVSX0HUxZsMtSTu.QmeJ0oaF.',
                'telp' => null,
            ),
            array(
                'nm_user' => 'kabid',
                'username' => 'kabid@gmail.com',
                'email' => 'kabid@gmail.com',
                'password' => '$2y$10$.ROlm4QAWfHncLuuV6Kc1.hT6xuOfVSX0HUxZsMtSTu.QmeJ0oaF.',
                'telp' => null,
            ),
            array(
                'nm_user' => 'sekretaris',
                'username' => 'sekretaris@gmail.com',
                'email' => 'sekretaris@gmail.com',
                'password' => '$2y$10$.ROlm4QAWfHncLuuV6Kc1.hT6xuOfVSX0HUxZsMtSTu.QmeJ0oaF.',
                'telp' => null,
            ),
            array(
                'nm_user' => 'kaban',
                'username' => 'kaban@gmail.com',
                'email' => 'kaban@gmail.com',
                'password' => '$2y$10$.ROlm4QAWfHncLuuV6Kc1.hT6xuOfVSX0HUxZsMtSTu.QmeJ0oaF.',
                'telp' => null,
            ),
            array(
                'nm_user' => 'arsip',
                'username' => 'arsip@gmail.com',
                'email' => 'arsip@gmail.com',
                'password' => '$2y$10$.ROlm4QAWfHncLuuV6Kc1.hT6xuOfVSX0HUxZsMtSTu.QmeJ0oaF.',
                'telp' => null,
            ),
            array(
                'nm_user' => 'petugas BAP',
                'username' => 'bap@gmail.com',
                'email' => 'bap@gmail.com',
                'password' => '$2y$10$.ROlm4QAWfHncLuuV6Kc1.hT6xuOfVSX0HUxZsMtSTu.QmeJ0oaF.',
                'telp' => null,
            ),
            array(
                'nm_user' => 'pembuat SK',
                'username' => 'sk@gmail.com',
                'email' => 'sk@gmail.com',
                'password' => '$2y$10$.ROlm4QAWfHncLuuV6Kc1.hT6xuOfVSX0HUxZsMtSTu.QmeJ0oaF.',
                'telp' => null,
            ),
            array(
                'nm_user' => 'Ketua TIM',
                'username' => 'ketua@gmail.com',
                'email' => 'ketua@gmail.com',
                'password' => '$2y$10$.ROlm4QAWfHncLuuV6Kc1.hT6xuOfVSX0HUxZsMtSTu.QmeJ0oaF.',
                'telp' => null,
            ),
            array(
                'nm_user' => 'Kelengkapan Berkas',
                'username' => 'berkas@gmail.com',
                'email' => 'berkas@gmail.com',
                'password' => '$2y$10$.ROlm4QAWfHncLuuV6Kc1.hT6xuOfVSX0HUxZsMtSTu.QmeJ0oaF.',
                'telp' => null,
            ),

            // User yang didaftarkan oleh admin
            array(
                'nm_user' => 'SITI HANIFAH',
                'username' => '3578064206810002',
                'email' => 'berkas1@gmail.com',
                'password' => Hash::make(3578064206810002),
                'telp' => null,
            ),
            array(
                'nm_user' => 'FAJAR RATIH',
                'username' => '197302182006042008',
                'email' => 'arsip1@gmail.com',
                'password' => Hash::make(197302182006042008),
                'telp' => null,
            ),
            array(
                'nm_user' => 'DHIMAS BANGUN ANGGORO',
                'username' => '3515130705910003',
                'email' => 'bap1@gmail.com',
                'password' => Hash::make(3515130705910003),
                'telp' => null,
            ),
            array(
                'nm_user' => 'BAGUS PRATAMA PUTRA',
                'username' => '3515131508890001',
                'email' => 'bap2@gmail.com',
                'password' => Hash::make(3515131508890001),
                'telp' => null,
            ),
            array(
                'nm_user' => 'LISTYA ASWARATIKA',
                'username' => '3372015707930002',
                'email' => 'sk1@gmail.com',
                'password' => Hash::make(3372015707930002),
                'telp' => null,
            ),
            array(
                'nm_user' => 'MIMIN MISDYAWATI',
                'username' => '197104152006042019',
                'email' => 'ketua1@gmail.com',
                'password' => Hash::make(197104152006042019),
                'telp' => null,
            ),
            array(
                'nm_user' => 'DIMAS NUSWANTORO',
                'username' => '198103142009021002',
                'email' => 'kabid1@gmail.com',
                'password' => Hash::make(198103142009021002),
                'telp' => null,
            ),
            array(
                'nm_user' => 'SUKEDI, S.H.',
                'username' => '198010042014121001',
                'email' => 'sekretaris1@gmail.com',
                'password' => Hash::make(198010042014121001),
                'telp' => null,
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
