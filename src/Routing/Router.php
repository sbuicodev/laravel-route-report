<?php

namespace SbuicoDev\LaravelRouteReport\Routing;

use SbuicoDev\LaravelRouteReport\Routing\RouteCollection;
use Illuminate\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Routing\Router as BaseRouter;

class Router extends BaseRouter
{
    public function __construct(Dispatcher $events, Container $container = null)
    {
        parent::__construct($events, $container);
        $this->routes = new RouteCollection;
    }
}
