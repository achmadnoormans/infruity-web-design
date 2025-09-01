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
        Schema::create('role', function (Blueprint $table) {
            $table->integer('id_role')->primary();
            $table->string('nm_role', 60);
            $table->text('description')->nullable();
            $table->integer('id_creator');
            $table->timestamps();
            $table->softDeletes();
        });

        \DB::table('role')->delete();

        \DB::table('role')->insert(array(
            [
                'id_role' => 1,
                'nm_role' => 'Administrator',
                'description' => 'Role Administrator',
                'id_creator' => 1
            ],
            [
                'id_role' => 2,
                'nm_role' => 'Data Management',
                'description' => 'Role Data Management',
                'id_creator' => 1
            ],
            [
                'id_role' => 3,
                'nm_role' => 'Store Admin',
                'description' => 'Role Store Admin',
                'id_creator' => 1
            ],
            [
                'id_role' => 4,
                'nm_role' => 'Store Crew 1',
                'description' => 'Role Store Crew 1',
                'id_creator' => 1
            ],
            [
                'id_role' => 5,
                'nm_role' => 'Store Crew 2',
                'description' => 'Role Store Crew 2',
                'id_creator' => 1
            ],  
            [
                'id_role' => 6,
                'nm_role' => 'Morning Booth Crew - 1',
                'description' => 'Role Store Crew 3',
                'id_creator' => 1
            ],

            [
                'id_role' => 7,
                'nm_role' => 'Morning Booth Crew - 2',
                'description' => 'Role Store Crew 3',
                'id_creator' => 1
            ],
            [
                'id_role' => 8,
                'nm_role' => 'Morning Booth Crew - 3',
                'description' => 'Role Store Crew 3',
                'id_creator' => 1
            ],
            [
                'id_role' => 9,
                'nm_role' => 'Morning Booth Crew - 4',
                'description' => 'Role Store Crew 3',
                'id_creator' => 1
            ],
            [
                'id_role' => 10,
                'nm_role' => 'Distribution Crew 1',
                'description' => 'Role Store Crew 3',
                'id_creator' => 1
            ],
            [
                'id_role' => 11,
                'nm_role' => 'Distribution Crew 2',
                'description' => 'Role Store Crew 3',
                'id_creator' => 1
            ],
            [
                'id_role' => 12,
                'nm_role' => 'Content Creator',
                'description' => 'Role Store Crew 3',
                'id_creator' => 1
            ],


            [
                'id_role' => 13,
                'nm_role' => 'Outlet Crew',
                'description' => 'Role Store Crew 3',
                'id_creator' => 1
            ],
            [
                'id_role' => 14,
                'nm_role' => 'Courier Crew',
                'description' => 'Role Store Crew 3',
                'id_creator' => 1
            ],
            [
                'id_role' => 15,
                'nm_role' => 'Helper Crew',
                'description' => 'Role Store Crew 3',
                'id_creator' => 1
            ]
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role');
    }
};
