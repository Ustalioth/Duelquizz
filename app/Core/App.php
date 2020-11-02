<?php

namespace App\Core;

use App\Core\Routing\Route;
use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;
use FastRoute\RouteCollector;

class App
{
    protected $rootDir = __DIR__ . '/../..';

    public function handle()
    {
        echo $this->disptach();
    }

    protected function disptach(): string
    {
        /** @var Route[] $routes */
        $routes = require($this->rootDir . '/config/routing.php');

        $dispatcher = simpleDispatcher(function(RouteCollector $r) use ($routes) {
            foreach ($routes as $route) {
                $r->addRoute(
                    $route->getMethod(),
                    $route->getPath(),
                    $route->getAction()
                );
            }
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = str_replace('/index.php', '', $_SERVER['REQUEST_URI']);
        $uri = !empty($uri) ? $uri : '/';

        $pos = strpos($uri, '?');
        if (false !== $pos) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new \Exception('L\'url spécifiée n\'a pas été trouvée !', 404);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new \Exception('Méthode non autorisée', 401);
                break;
            case Dispatcher::FOUND:
                [,$action, $vars] = $routeInfo;

                if (!class_exists($action)) {
                    throw new \RuntimeException('Class ' . $action . 'does not exists!');
                }

                return call_user_func_array(new $action(), $vars);
                break;
        }

    }
}
