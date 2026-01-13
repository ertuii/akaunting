<?php

namespace Modules\CostOverview\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class Main extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViews();
        $this->loadTranslations();
        $this->loadMigrations();
        $this->registerRoutes();
        $this->registerMenuItems();
        $this->registerRouteModelBindings();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadConfig();
    }

    /**
     * Load views.
     *
     * @return void
     */
    public function loadViews()
    {
        $viewPath = module_path('CostOverview', 'Resources/views');

        $this->loadViewsFrom($viewPath, 'cost-overview');
    }

    /**
     * Load translations.
     *
     * @return void
     */
    public function loadTranslations()
    {
        $langPath = module_path('CostOverview', 'Resources/lang');

        $this->loadTranslationsFrom($langPath, 'cost-overview');
    }

    /**
     * Load migrations.
     *
     * @return void
     */
    public function loadMigrations()
    {
        $this->loadMigrationsFrom(module_path('CostOverview', 'Database/Migrations'));
    }

    /**
     * Load config.
     *
     * @return void
     */
    public function loadConfig()
    {
        $configPath = module_path('CostOverview', 'Config/config.php');

        if (file_exists($configPath)) {
            $this->mergeConfigFrom($configPath, 'cost-overview');
        }
    }

    /**
     * Register routes.
     *
     * @return void
     */
    public function registerRoutes()
    {
        $routesPath = module_path('CostOverview', 'Routes');

        // Admin routes
        if (file_exists($routesPath . '/admin.php')) {
            Route::middleware(['admin'])
                ->prefix('{company_id}')
                ->namespace('Modules\CostOverview\Http\Controllers')
                ->group($routesPath . '/admin.php');
        }

        // Portal routes
        if (file_exists($routesPath . '/portal.php')) {
            Route::middleware(['portal'])
                ->prefix('{company_id}/portal')
                ->namespace('Modules\CostOverview\Http\Controllers')
                ->group($routesPath . '/portal.php');
        }
    }

    /**
     * Register menu items.
     *
     * @return void
     */
    public function registerMenuItems()
    {
        // Register menu item for admin area
        $this->app['events']->listen('menu.sales.created', function($menu) {
            $menu->route('cost-overviews.index', trans('cost-overview::general.name'), [], 35, [
                'icon' => 'fa fa-file-text-o',
            ]);
        });

        // Register menu item for portal area
        $this->app['events']->listen('menu.portal.created', function($menu) {
            $menu->route('portal.cost-overviews.index', trans('cost-overview::general.name'), [], 25, [
                'icon' => 'fa fa-file-text-o',
            ]);
        });
    }

    /**
     * Register route model bindings.
     *
     * @return void
     */
    public function registerRouteModelBindings()
    {
        Route::bind('cost_overview', function ($value) {
            return \Modules\CostOverview\Models\CostOverview::find($value) ?? abort(404);
        });
    }
}
