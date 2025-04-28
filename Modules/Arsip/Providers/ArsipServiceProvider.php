<?php

namespace Modules\Arsip\Providers;

use Illuminate\Support\ServiceProvider;
class ArsipServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('Arsip', 'Database/Migrations'));
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
            module_path('Arsip', 'Config/config.php') => config_path('arsip.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Arsip', 'Config/config.php'), 'arsip'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/arsip');

        $sourcePath = module_path('Arsip', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/arsip';
        }, \Config::get('view.paths')), [$sourcePath]), 'arsip');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/arsip');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'arsip');
        } else {
            $this->loadTranslationsFrom(module_path('Arsip', 'Resources/lang'), 'arsip');
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
            $this->loadFactoriesFrom(module_path('Arsip', 'Database/factories'));
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
