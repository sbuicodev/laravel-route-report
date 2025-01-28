<?php

namespace SbuicoDev\LaravelRouteReport\Providers;

use Illuminate\Support\ServiceProvider;
use SbuicoDev\LaravelRouteReport\Console\Commands\RouteReport;
use SbuicoDev\LaravelRouteReport\Routing\Router;

class RouteReportServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->runningInConsole() && !empty(request()->server('argv')[1]) && request()->server('argv')[1] === 'route:report') {
            $this->app->bind('router', Router::class);
        }
        
        $this->commands([
            RouteReport::class,
        ]);
    }
}
