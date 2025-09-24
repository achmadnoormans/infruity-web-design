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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nickname')->nullable();
            $table->text('nik')->nullable();
            $table->text('contact')->nullable();
            $table->unsignedBigInteger('position_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->text('email')->nullable();
            $table->date('date_in')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->integer('is_kurir')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('staff')->insert([
            [
                'name' => 'Ahmad Saputra',
                'nickname' => 'Ahmad',
                'nik' => '3201010101010001',
                'contact' => '081234567891',
                'position_id' => 1,
                'department_id' => 1,
                'email' => 'ahmad@example.com',
                'date_in' => '2023-01-10',
                'description' => 'Staff IT',
                'status' => 'aktif',
                'gender' => 'male',
                'is_kurir' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Budi Santoso',
                'nickname' => 'Budi',
                'nik' => '3201010101010002',
                'contact' => '081234567892',
                'position_id' => 2,
                'department_id' => 1,
                'email' => 'budi@example.com',
                'date_in' => '2022-05-15',
                'description' => 'Staff HRD',
                'status' => 'aktif',
                'gender' => 'male',
                'is_kurir' => 0,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Citra Lestari',
                'nickname' => 'Citra',
                'nik' => '3201010101010003',
                'contact' => '081234567893',
                'position_id' => 3,
                'department_id' => 2,
                'email' => 'citra@example.com',
                'date_in' => '2021-08-20',
                'description' => 'Staff Finance',
                'status' => 'nonaktif',
                'gender' => 'female',
                'is_kurir' => 0,
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
        Schema::dropIfExists('staff');
    }
};
