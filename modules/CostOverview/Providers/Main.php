<?php

namespace Modules\CostOverview\Providers;

use Illuminate\Support\ServiceProvider;

class Main extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutes();
    }

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'cost-overview');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'cost-overview');
        
        // Register menu listener
        $this->app['events']->listen(
            'App\Events\Menu\AdminCreated',
            'Modules\CostOverview\Listeners\ShowInMenu@handle'
        );
    }

    /**
     * Load routes.
     *
     * @return void
     */
    public function loadRoutes()
    {
        if (app()->routesAreCached()) {
            return;
        }

        $routes = [
            'admin.php',
        ];

        foreach ($routes as $route) {
            $route_path = __DIR__ . '/../Routes/' . $route;

            if (! is_file($route_path)) {
                continue;
            }

            \Route::middleware(['web', 'admin'])
                ->namespace('Modules\CostOverview\Http\Controllers')
                ->prefix('cost-overviews')
                ->group($route_path);
        }
    }
}
