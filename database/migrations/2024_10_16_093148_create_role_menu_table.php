<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('label');
            $table->timestamps();
            $table->softDeletes();
        });

        $exclude = ['data', 'ajax', 'debug', 'livewire', 'ignition'];
        $routes = collect(Route::getRoutes())
            ->filter(fn($route) => !Str::contains($route->uri(), $exclude))
            ->map(fn($route) => $route->getName())
            ->filter()
            ->values()
            ->all();

        foreach ($routes as $r) {
            Permission::firstOrCreate(['name' => $r], ['label' => ucfirst(str_replace('.', ' ', $r))]);
        }

        Schema::create('role_menu', function (Blueprint $table) {
            $table->integer('id_rm', true);
            $table->integer('id_role')->index('role');
            $table->string('permission')->index('menu');
            $table->timestamps();
            $table->softDeletes();
        });

        \DB::table('role_menu')->insert(array(
            [
                'id_role' => 2,
                'permission' => 'products.index',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_role' => 2,
                'permission' => 'products.create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_role' => 2,
                'permission' => 'products.edit',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_role' => 2,
                'permission' => 'products.destroy',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_menu');
        Schema::dropIfExists('permissions');
    }
};
