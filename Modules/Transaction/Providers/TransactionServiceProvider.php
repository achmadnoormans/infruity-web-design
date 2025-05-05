<?php

namespace Modules\Transaction\Providers;

use Illuminate\Support\ServiceProvider;
class TransactionServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('Transaction', 'Database/Migrations'));
        $this->loadViewsFrom(module_path('Transaction', 'Resources/views'), 'Transaction');
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
            module_path('Transaction', 'Config/config.php') => config_path('transaction.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Transaction', 'Config/config.php'), 'transaction'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/transaction');

        $sourcePath = module_path('Transaction', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/transaction';
        }, \Config::get('view.paths')), [$sourcePath]), 'transaction');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/transaction');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'transaction');
        } else {
            $this->loadTranslationsFrom(module_path('Transaction', 'Resources/lang'), 'transaction');
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
            $this->loadFactoriesFrom(module_path('Transaction', 'Database/factories'));
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
