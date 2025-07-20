<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crm_point_schedule', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('frequency'); // Frequency in days
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('crm_point_frequency', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('crm_point_frequency')->insert([
            [
                'name' => '3 Months',
                'description' => 'Every 3 months',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '6 Months',
                'description' => 'Every 6 months',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '9 Months',
                'description' => 'Every 9 months',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '1 Year',
                'description' => 'Every year',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Custom',
                'description' => 'Custom period',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('crm_point_schedule')->insert([
            [
                'start_date' => now(),
                'end_date' => now()->addDays(90),
                'frequency' => 1,
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
        Schema::dropIfExists('crm_point_schedule');
        Schema::dropIfExists('crm_point_frequency');
    }
};
