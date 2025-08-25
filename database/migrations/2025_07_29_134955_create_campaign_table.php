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
        Schema::create('crm_campaign', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('frequency'); // Frequency in days
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('type_promo', ['discount', 'price'])->default('discount');
            $table->integer('value')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('crm_campaign')->insert([
            [
                'name' => 'Hari Kemerdekaan',
                'start_date' => now(),
                'end_date' => now()->addDays(1),
                'frequency' => 1,
                'status' => 'active',
                'type_promo' => 'discount',
                'value' => 10,
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
        Schema::dropIfExists('crm_campaign');
    }
};
