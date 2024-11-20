<?php

namespace SbuicoDev\LaravelRouteReport\Console\Commands;

use SbuicoDev\LaravelRouteReport\Routing\RouteCollection;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use ReflectionException;
use SbuicoDev\LaravelRouteReport\Routing\Router;

class RouteReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'route:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyzes route definitions and provides a report on configuration anomalies: duplicate routes, non-existent actions, etc. Works only in development environment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (App::environment() === 'production') {
            $this->error("This command is only available in development environment");
            return Command::FAILURE;
        }

        App::bind('router', Router::class);

        $this->line('-----------------------------------');
        $this->line(' Route Analysis ');
        $this->line('-----------------------------------');
        /** @var RouteCollection */
        $routes = Route::getRoutes();

        /*
         |---------------------------------
         | Report of duplicate routes
         |---------------------------------
         | A custom router is used to get a list of duplicate routes
         |
         */
        $duplicates = $routes->getDuplicates();
        if ($duplicates->isNotEmpty()) {
            $this->warn('Sono state trovate le seguenti route duplicate:');
            $this->table(['Route', 'Method', 'Actions'], $duplicates->map(fn($duplicate) => [
                $duplicate['uri'],
                $duplicate['method'],
                implode(PHP_EOL, $duplicate['actions']->toArray()),
            ]), 'box');
        }
        /*
         |--------------------------------------
         | Report of non-existent actions
         |--------------------------------------
         | Method and/or controller existence is checked for each route
         | in order to determine which point to non-existent actions.
         |
         */
        $nonExistingActions = [];
        foreach ($routes as $route) {
            if (!$this->actionExists($route->getActionName())) {
                $nonExistingActions[] = [
                    'uri' => $route->getDomain() . $route->uri(),
                    'method' => implode('|', $route->methods()),
                    'action' => $route->getActionName()
                ];
            }
        }
        if (!empty($nonExistingActions)) {
            $this->warn('Sono state trovate le seguenti route con azioni non esistenti:');
            $this->table(['Route', 'Method', 'Action'], $nonExistingActions, 'box');
        }

        return Command::SUCCESS;
    }

    /**
     * Checks if the specified action exists.
     * @param string $action Action, following the format: controller@method
     */
    private function actionExists(string $action): bool
    {
        $parts = explode('@', $action);

        // Check if the action has a valid format
        if (empty($parts) || count($parts) > 2) {
            return false;
        }

        try {
            $controller = $parts[0];
            $reflector = new ReflectionClass($controller);

            // No specific method provided, only checking if the controller is invokable
            if (count($parts) === 1) {
                return $reflector->hasMethod('__invoke');
            }

            // Check if the specified method exists
            $method = $parts[1];
            if ($reflector->hasMethod($method)) {
                $methodReflection = $reflector->getMethod($method);
                return $methodReflection->isPublic();
            }
        } catch (ReflectionException $e) {
        }

        return false;
    }
}
