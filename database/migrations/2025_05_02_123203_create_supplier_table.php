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
        Schema::create('supplier', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('pic_name');
            $table->string('pic_whatsapp');
            $table->text('address')->nullable();
            $table->string('email')->nullable()->unique();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('supplier')->insert([
            [
                'name' => 'Bakul Buah 1',
                'pic_name' => 'Achmad Noorman',
                'pic_whatsapp' => '081230607050',
                'address' => 'Gresik Kota Baru',
                'email' => 'noorman@chans.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bakul Buah 2',
                'pic_name' => 'Achmad Noorman',
                'pic_whatsapp' => '081230607050',
                'address' => 'Gresik Kota Baru',
                'email' => 'noorman@gans.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bakul Buah 3',
                'pic_name' => 'Achmad Noorman',
                'pic_whatsapp' => '081230607050',
                'address' => 'Gresik Kota Baru',
                'email' => 'noorman@cuyy.com',
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
        Schema::dropIfExists('supplier');
    }
};
