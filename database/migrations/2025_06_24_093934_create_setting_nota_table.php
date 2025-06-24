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
        Schema::create('setting_nota', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('header')->nullable();
            $table->string('footer')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('setting_nota')->insert([
            [
                'logo' => 'default_logo.png',
                'header' => 'in!fruity<br>Jl. Raya No. 1, Jakarta<br>Telp: (021) 12345678',
                'footer' => 'Terima kasih telah berbelanja!',
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
        Schema::dropIfExists('setting_nota');
    }
};
