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
            $table->bigInteger('city')->nullable();;
            $table->bigInteger('district')->nullable();;
            $table->bigInteger('village')->nullable();;
            $table->text('gender')->nullable();
            $table->text('address')->nullable();
            $table->text('email')->nullable();
            $table->enum('type', ['reguler', 'member', 'mitra'])->default('reguler');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_address', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->text('address')->nullable();
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
                'province' => 35,
                'city' => 3525,
                'district' => 352501,
                'village' => 3525012001,
                'gender' => 'male',
                'address' => 'Jl. Merdeka No. 10, Jakarta',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2025-07-01 14:20:20',
                'updated_at' => now(),
            ],
            [
                'name' => 'Siti Nurhaliza',
                'code' => 'PLG250500002',
                'whatsapp' => '081298765432',
                'birth_of_date' => '1985-12-11',
                'province' => 35,
                'city' => 3525,
                'district' => 352502,
                'village' => 3525022002,
                'gender' => 'female',
                'address' => 'Jl. Sudirman No. 22, Bandung',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2025-07-02 14:20:20',
                'updated_at' => now(),
            ],
            [
                'name' => 'Budi Santoso',
                'code' => 'PLG250500003',
                'whatsapp' => '081356789012',
                'birth_of_date' => '1988-03-14',
                'province' => 35,
                'city' => 3525,
                'district' => 352503,
                'village' => 3525032003,
                'gender' => 'male',
                'address' => 'Jl. Pahlawan No. 5, Surabaya',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2025-07-02 14:20:20',
                'updated_at' => now(),
            ],
            [
                'name' => 'Dewi Lestari',
                'code' => 'PLG250500004',
                'whatsapp' => '081478901234',
                'birth_of_date' => '1992-07-30',
                'province' => 35,
                'city' => 3525,
                'district' => 352504,
                'village' => 3525042004,
                'gender' => 'female',
                'address' => 'Jl. Kebon Jeruk No. 11, Jakarta',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2025-07-03 14:20:20',
                'updated_at' => now(),
            ],
            [
                'name' => 'Rudi Hartono',
                'code' => 'PLG250500005',
                'whatsapp' => '081589012345',
                'birth_of_date' => '1995-09-05',
                'province' => 35,
                'city' => 3525,
                'district' => 352505,
                'village' => 3525052005,
                'gender' => 'male',
                'address' => 'Jl. Merdeka No. 5, Jakarta',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2025-07-03 14:20:20',
                'updated_at' => now(),
            ],
            [
                'name' => 'Lina Marlina',
                'code' => 'PLG250500006',
                'whatsapp' => '081678901234',
                'birth_of_date' => '1993-11-20',
                'province' => 35,
                'city' => 3525,
                'district' => 352506,
                'village' => 3525062006,
                'gender' => 'female',
                'address' => 'Jl. Kebon Jeruk No. 12, Jakarta',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2025-07-04 14:20:20',
                'updated_at' => now(),
            ],
            [
                'name' => 'Agus Prabowo',
                'code' => 'PLG250500007',
                'whatsapp' => '081789012345',
                'birth_of_date' => '1991-04-15',
                'province' => 35,
                'city' => 3525,
                'district' => 352507,
                'village' => 3525072007,
                'gender' => 'male',
                'address' => 'Jl. Merdeka No. 5, Jakarta',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2025-07-04 14:20:20',
                'updated_at' => now(),
            ],
            [
                'name' => 'Citra Ayu',
                'code' => 'PLG250500008',
                'whatsapp' => '081890123456',
                'birth_of_date' => '1994-08-25',
                'province' => 35,
                'city' => 3525,
                'district' => 352508,
                'village' => 3525082008,
                'gender' => 'female',
                'address' => 'Jl. Kebon Jeruk No. 12, Jakarta',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2025-07-04 14:20:20',
                'updated_at' => now(),
            ]
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
