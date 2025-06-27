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
            $table->boolean('is_using_logo')->default(false);
            $table->string('header')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('brand_address')->nullable();
            $table->string('brand_social_media')->nullable();
            $table->boolean('is_using_cashier')->default(false);
            $table->boolean('is_using_customer')->default(false);
            $table->boolean('is_using_date')->default(false);
            $table->boolean('is_using_invoice_number')->default(false);
            $table->string('footer')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('setting_nota')->insert([
            [
                'brand_name' => 'in!fruity',
                'brand_address' => 'Jl. Raya No. 1, Jakarta',
                'brand_social_media' => 'https://www.instagram.com/infruity',
                'is_using_cashier' => true,
                'is_using_customer' => true,
                'is_using_date' => true,
                'is_using_invoice_number' => true,
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
