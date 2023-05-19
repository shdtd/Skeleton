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
     * Description
     *
     * @return object
     */
    protected static function getInstance(): object
    {
        return new \Libraries\Route\Router();
    }
}
