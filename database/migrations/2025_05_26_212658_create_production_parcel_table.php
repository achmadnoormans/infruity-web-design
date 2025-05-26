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
        Schema::create('production_parcel', function (Blueprint $table) {
            $table->id();
            $table->string('production_number', 20);
            $table->date('production_date');
            $table->enum('status', ['draft', 'posting'])->default('posting');
            $table->integer('budget')->default(0);
            $table->integer('quantity')->default(1);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_parcel');
    }
};
