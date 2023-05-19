<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Facades
 * @package  Facade
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries\Facades;

/**
 * Facade class
 * Description
 *
 * @category Facades
 * @package  Facade
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
abstract class Facade
{
    /**
     * Description
     *
     * @return object
     */
    abstract protected static function getInstance(): object;

    /**
     * Description
     *
     * @param string       $name      Description.
     * @param array<mixed> $arguments Description.
     *
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments): mixed
    {
        $instance = static::getInstance();
        return $instance->$name(...$arguments);
    }
}
