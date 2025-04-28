<?php

namespace Modules\Permohonan\Providers;

use Illuminate\Support\ServiceProvider;
class PermohonanServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('Permohonan', 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('Permohonan', 'Config/config.php') => config_path('permohonan.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Permohonan', 'Config/config.php'), 'permohonan'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/permohonan');

        $sourcePath = module_path('Permohonan', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/permohonan';
        }, \Config::get('view.paths')), [$sourcePath]), 'permohonan');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/permohonan');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'permohonan');
        } else {
            $this->loadTranslationsFrom(module_path('Permohonan', 'Resources/lang'), 'permohonan');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
   
    public function registerFactories()
    {
        if (!app()->environment('production') && $this->app->runningInConsole()) {
            // Menggunakan loadFactoriesFrom untuk factory berbasis class di Laravel 8
            $this->loadFactoriesFrom(module_path('Permohonan', 'Database/factories'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
