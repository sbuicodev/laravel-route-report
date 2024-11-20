<?php

namespace SbuicoDev\LaravelRouteReport\Routing;

use Illuminate\Routing\RouteCollection as BaseRouteCollection;
use Illuminate\Support\Collection;

class RouteCollection extends BaseRouteCollection
{
    protected Collection $duplicates;

    public function __construct()
    {
        $this->duplicates = new Collection();
    }

    protected function addToCollections($route)
    {
        $domainAndUri = $route->getDomain() . $route->uri();

        foreach ($route->methods() as $method) {
            /*|-----------------------------------------------------
              | Intercepting duplicate routes
              |-----------------------------------------------------
            */
            if (isset($this->routes[$method][$domainAndUri])) {
                $this->duplicates->push(['method' => $method, 'uri' => $domainAndUri, 'actions' => collect()]);

                $item = $this->duplicates->where('method', $method)->where('uri', $domainAndUri)->first();
                if ($item['actions']->isEmpty()) {
                    $item['actions']->push($this->routes[$method][$domainAndUri]->getActionName());
                }

                $item['actions']->push($route->getActionName());
            }
            /*|----------------------------------------------------- */

            $this->routes[$method][$domainAndUri] = $route;
        }

        $this->allRoutes[$method . $domainAndUri] = $route;
    }

    public function getDuplicates(): Collection
    {
        return $this->duplicates;
    }
}
