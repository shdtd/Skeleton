<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Facades
 * @package  Route
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries\Facades;

use Libraries\Route\Router;

/**
 * Routes class
 * Description
 *
 * @category Facades
 * @package  Route
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class Route extends Facade
{

    /**
     * Instanse
     *
     * @var Router $instance
     */
    protected static Router $instance;

    /**
     * Description
     *
     * @return object
     */
    protected static function getInstance(): object
    {
        if (isset(self::$instance) === false) {
            self::$instance = new Router();
        }

        return self::$instance;
    }
}
