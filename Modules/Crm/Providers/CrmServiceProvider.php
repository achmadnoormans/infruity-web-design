<?php

namespace Modules\Crm\Providers;

use Illuminate\Support\ServiceProvider;
class CrmServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('Crm', 'Database/Migrations'));
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
            module_path('Crm', 'Config/config.php') => config_path('crm.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Crm', 'Config/config.php'), 'crm'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/crm');

        $sourcePath = module_path('Crm', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/crm';
        }, \Config::get('view.paths')), [$sourcePath]), 'crm');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/crm');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'crm');
        } else {
            $this->loadTranslationsFrom(module_path('Crm', 'Resources/lang'), 'crm');
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
            $this->loadFactoriesFrom(module_path('Crm', 'Database/factories'));
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
