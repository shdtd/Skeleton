<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category AppRoute
 * @package  Router
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries\Route;

use Libraries\Registry;
use Libraries\Requests\Request;

/**
 * Router class
 * Description
 *
 * @category AppRoute
 * @package  Router
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class Router
{

    /**
     * Description
     *
     * @var string $prefix
     */
    protected static string $prefix = '';

    /**
     * Description
     *
     * @var Registry $peg
     */
    protected Registry $reg;

    /**
     * Description
     *
     * @var Request $request
     */
    protected Request $request;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reg     = Registry::instance();
        $this->request = $this->reg->getRequest();
    }

    /**
     * Description
     *
     * @param string   $prefix Description.
     * @param callable $routes Description.
     *
     * @return void
     */
    public function group(string $prefix, callable $routes): void
    {
        self::$prefix = $this->request->getPrefix() . '/'. $prefix;
        $routes();
        self::$prefix = '';
    }

    /**
     * Description
     *
     * @param string $path    Description.
     * @param string $command Description.
     * @param string $action  Description.
     *
     * @return void
     */
    public function get(
        string $path,
        string $command,
        string $action
    ): void {
        $this->addRoute('GET', $path, $command, $action);
    }

    /**
     * Description
     *
     * @param string $path    Description.
     * @param string $command Description.
     * @param string $action  Description.
     *
     * @return void
     */
    public function head(
        string $path,
        string $command,
        string $action
    ): void {
        $this->addRoute('HEAD', $path, $command, $action);
    }

    /**
     * Description
     *
     * @param string $path    Description.
     * @param string $command Description.
     * @param string $action  Description.
     *
     * @return void
     */
    public function post(
        string $path,
        string $command,
        string $action
    ): void {
        $this->addRoute('POST', $path, $command, $action);
    }

    /**
     * Description
     *
     * @param string $path    Description.
     * @param string $command Description.
     * @param string $action  Description.
     *
     * @return void
     */
    public function put(
        string $path,
        string $command,
        string $action
    ): void {
        $this->addRoute('PUT', $path, $command, $action);
    }

    /**
     * Description
     *
     * @param string $path    Description.
     * @param string $command Description.
     * @param string $action  Description.
     *
     * @return void
     */
    public function patch(
        string $path,
        string $command,
        string $action
    ): void {
        $this->addRoute('PATCH', $path, $command, $action);
    }

    /**
     * Description
     *
     * @param string $path    Description.
     * @param string $command Description.
     * @param string $action  Description.
     *
     * @return void
     */
    public function delete(
        string $path,
        string $command,
        string $action
    ): void {
        $this->addRoute('DELETE', $path, $command, $action);
    }

    /**
     * Description
     *
     * @param string $path    Description.
     * @param string $command Description.
     * @param string $action  Description.
     *
     * @return void
     */
    public function options(
        string $path,
        string $command,
        string $action
    ): void {
        $this->addRoute('OPTIONS', $path, $command, $action);
    }

    /**
     * Description
     *
     * @param string $method  Description.
     * @param string $path    Description.
     * @param string $command Description.
     * @param string $action  Description.
     *
     * @return void
     */
    public function addRoute(
        string $method,
        string $path,
        string $command,
        string $action
    ): void {
        if (empty(self::$prefix) === true) {
            self::$prefix = $this->request->getPrefix();
        }

        if (empty($path) === true || $path[0] !== '/') {
            $path = '/' . $path;
        }

        $path = self::$prefix . $path;

        if ($path !== '/' && $path[(strlen($path) - 1)] === '/') {
            $path = substr($path, 0, -1);
        }

        $route = new Route($method, $path, $command, $action);
        $this->reg->getRouteCollection()->add($route);
    }
}
