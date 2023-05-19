<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category AppRoute
 * @package  RouteCollection
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries\Route;

use Libraries\Config;
use Libraries\Registry;

/**
 * RouteCollection class
 * Description
 *
 * @category AppRoute
 * @package  RouteCollection
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class RouteCollection
{

    /**
     * Description
     *
     * @var array<string, array<Route>> $routes
     */
    protected array $routes = [];

    /**
     * Description
     *
     * @param Route $route Description.
     *
     * @return void
     */
    public function add(Route $route): void
    {
        $this->routes[$route->getMethod()][$route->getPath()] = $route;
    }

    /**
     * Description
     *
     * @param string $method Description.
     *
     * @return Config
     */
    public function getCommands(string $method): Config
    {
        if (isset($this->routes[$method]) === true) {
            return new Config($this->routes[$method]);
        } else {
            return new Config([]);
        }
    }

    /**
     * Description
     *
     * @param string $method Description.
     * @param string $path   Description.
     *
     * @return Route|null
     */
    public function getRouteByPath(string $method, string $path): Route|null
    {
        $pathParts      = explode('/', $path);
        $pathParts      = array_diff($pathParts, ['']);
        $count          = count($pathParts);
        $routes         = [];
        $request        = Registry::instance()->getRequest();
        $routesByMethod = [];

        if (isset($this->routes[$method]) === true) {
            $routesByMethod = $this->routes[$method];
        }

        foreach ($routesByMethod as $route) {
            if ($route->getPath() === '/' . implode('/', $pathParts)) {
                return $route;
            }

            if ($count !== $route->getCount()
                || empty($route->getParameters()) === true
            ) {
                continue;
            }

            $parameters = array_diff($pathParts, $route->getParts());

            if (count($parameters) !== count($route->getParameters())) {
                continue;
            }

            $routes[] = $route;
        }

        usort(
            $routes,
            function ($a, $b) {
                if (count($a->getParameters()) === count($b->getParameters())) {
                    return 0;
                }

                if (count($a->getParameters()) < count($b->getParameters())) {
                    return -1;
                } else {
                    return 1;
                };
            }
        );

        if (empty($routes[0]) === true) {
            return null;
        }

        $keys      = $routes[0]->getParameters();
        $values    = array_diff($pathParts, $routes[0]->getParts());
        $values    = array_values($values);
        $keysCount = count($keys);

        for ($i = 0; $i < $keysCount; $i++) {
            $request->addParameters($keys[$i], $values[$i]);
        }

        return $routes[0];
    }
}
