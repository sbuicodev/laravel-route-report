<?php

namespace SbuicoDev\LaravelRouteReport\Providers;

use Illuminate\Support\ServiceProvider;
use SbuicoDev\LaravelRouteReport\Console\Commands\RouteReport;
use SbuicoDev\LaravelRouteReport\Routing\Router;

class RouteReportServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            RouteReport::class,
        ]);
    }
}
