<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Controllers
 * @package  AppController
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries\Controllers;

use Command\ErrorCommand;
use Libraries\ComponentDescriptor;
use Libraries\Errors\AppException;
use Libraries\Registry;
use Libraries\Requests\Request;

/**
 * AppController class
 * Description
 *
 * @category Controllers
 * @package  AppController
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class AppController
{

    /**
     * Description
     *
     * @var string $defaultcmd
     */
    private static string $defaultcmd = ErrorCommand::class;

    /**
     * Description
     *
     * @var string $defaultaction
     */
    private static string $defaultaction = '404';

    /**
     * Description
     *
     * @param Request $request Description.
     *
     * @return CommandController
     */
    public function getCommand(Request $request): CommandController
    {
        try {
            $descriptor = $this->getDescriptor($request);
            Registry::instance()->setDescriptor($descriptor);
            $cmd = $descriptor->getCommand();
        } catch (AppException $e) {
            $request->addFeedback($e->getMessage());
            return new self::$defaultcmd();
        }

        return $cmd;
    }

    /**
     * Description
     *
     * @return string
     */
    public function getAction(): string
    {
        try {
            $descriptor = Registry::instance()->getDescriptor();
            $action     = $descriptor->getAction();
        } catch (AppException $e) {
            return self::$defaultaction;
        }

        return $action;
    }

    /**
     * Description
     *
     * @param Request $request Description.
     *
     * @throws AppException Descriptor not found.
     * @return ComponentDescriptor
     */
    public function getDescriptor(Request $request): ComponentDescriptor
    {
        $path     = $request->getPath();
        $method   = Registry::instance()->getRequestMethod();
        $reg      = Registry::instance();
        $routeCol = $reg->getRouteCollection();
        $route    = $routeCol->getRouteByPath($method, $path);

        if (isset($route) === false) {
            throw new AppException('Descriptor not found for ' . $path);
        }

        $descriptor = new ComponentDescriptor(
            $route->getCommand(),
            $route->getAction()
        );

        return $descriptor;
    }
}
