<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer('province');
            $table->integer('city');
            $table->integer('district');
            $table->integer('village');
            $table->text('gender');
            $table->text('address')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('customer')->insert([
            [
                'name' => 'Andi Setiawan',
                'code' => 'PLG25050100001',
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
                'code' => 'PLG25050100002',
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
                'code' => 'PLG25050100003',
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
                'name' => 'Rina Marlina',
                'code' => 'PLG25050100004',
                'whatsapp' => '081367890123',
                'birth_of_date' => '1992-07-09',
                'province' => 12,
                'city' => 1275,
                'district' => 1275030,
                'village' => 1275030001,
                'gender' => 'female',
                'address' => 'Jl. Ahmad Yani No. 30, Medan',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dedi Gunawan',
                'code' => 'PLG25050100005',
                'whatsapp' => '081378901234',
                'birth_of_date' => '1983-10-22',
                'province' => 34,
                'city' => 3401,
                'district' => 3401040,
                'village' => 3401040004,
                'gender' => 'male',
                'address' => 'Jl. Gajah Mada No. 15, Yogyakarta',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lisa Anggraini',
                'code' => 'PLG25050100006',
                'whatsapp' => '081389012345',
                'birth_of_date' => '1995-01-30',
                'province' => 36,
                'city' => 3603,
                'district' => 3603070,
                'village' => 3603070002,
                'gender' => 'female',
                'address' => 'Jl. Mawar No. 8, Serang',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rizky Ramadhan',
                'code' => 'PLG25050100007',
                'whatsapp' => '081390123456',
                'birth_of_date' => '1991-08-05',
                'province' => 33,
                'city' => 3374,
                'district' => 3374020,
                'village' => 3374020003,
                'gender' => 'male',
                'address' => 'Jl. Slamet Riyadi No. 12, Solo',
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
