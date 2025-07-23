<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('code')->nullable();
            $table->text('whatsapp')->nullable();
            $table->date('birth_of_date')->nullable();
            $table->integer('province')->nullable();;
            $table->integer('city')->nullable();;
            $table->integer('district')->nullable();;
            $table->integer('village')->nullable();;
            $table->text('gender')->nullable();
            $table->text('address')->nullable();
            $table->text('email')->nullable();
            $table->enum('type', ['reguler', 'member', 'mitra'])->default('reguler');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('customer')->insert([
            [
                'name' => 'Andi Setiawan',
                'code' => 'PLG250500001',
                'whatsapp' => '081234567890',
                'birth_of_date' => '1990-05-20',
                'province' => 31,
                'city' => 3171,
                'district' => 3171020,
                'village' => 3171020001,
                'gender' => 'male',
                'address' => 'Jl. Merdeka No. 10, Jakarta',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siti Nurhaliza',
                'code' => 'PLG250500002',
                'whatsapp' => '081298765432',
                'birth_of_date' => '1985-12-11',
                'province' => 32,
                'city' => 3273,
                'district' => 3273160,
                'village' => 3273160002,
                'gender' => 'female',
                'address' => 'Jl. Sudirman No. 22, Bandung',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Budi Santoso',
                'code' => 'PLG250500003',
                'whatsapp' => '081356789012',
                'birth_of_date' => '1988-03-14',
                'province' => 35,
                'city' => 3578,
                'district' => 3578020,
                'village' => 3578020003,
                'gender' => 'male',
                'address' => 'Jl. Pahlawan No. 5, Surabaya',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dewi Lestari',
                'code' => 'PLG250500004',
                'whatsapp' => '081478901234',
                'birth_of_date' => '1992-07-30',
                'province' => 36,
                'city' => 3671,
                'district' => 3671020,
                'village' => 3671020004,
                'gender' => 'female',
                'address' => 'Jl. Kebon Jeruk No. 12, Jakarta',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rudi Hartono',
                'code' => 'PLG250500005',
                'whatsapp' => '081589012345',
                'birth_of_date' => '1995-09-05',
                'province' => 37,
                'city' => 3773,
                'district' => 3773160,
                'village' => 3773160005,
                'gender' => 'male',
                'address' => 'Jl. Merdeka No. 5, Jakarta',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lina Marlina',
                'code' => 'PLG250500006',
                'whatsapp' => '081678901234',
                'birth_of_date' => '1993-11-20',
                'province' => 38,
                'city' => 3871,
                'district' => 3871020,
                'village' => 3871020006,
                'gender' => 'female',
                'address' => 'Jl. Kebon Jeruk No. 12, Jakarta',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
