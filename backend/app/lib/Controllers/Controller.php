<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Controllers
 * @package  Controller
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries\Controllers;

use Libraries\Registry;
use Libraries\ApplicationHelper;

class Controller
{

    /**
     * Description
     *
     * @var Registry $reg
     */
    protected Registry $reg;

    /**
     * Constructor is private
     */
    private function __construct()
    {
        $this->reg = Registry::instance();
    }

    /**
     * Description
     *
     * @param string $requestMethod Description.
     * @param string $requestUri    Description.
     *
     * @return void
     */
    public static function run(
        string $requestMethod = 'GET',
        string $requestUri = '/'
    ): void {
        $instance = new self();
        $instance->reg->setRequestMethod(
            strtoupper(($_SERVER['REQUEST_METHOD'] ?? $requestMethod))
        );
        $instance->reg->setRequestUri(
            ($_SERVER['REQUEST_URI'] ?? $requestUri)
        );
        $helper = new ApplicationHelper();
        $helper->init();
        $instance->handleRequest();
    }

    /**
     * Description
     *
     * @return void
     */
    public function handleRequest(): void
    {
        $request    = $this->reg->getRequest();
        $controller = $this->reg->getAppController();
        $cmd        = $controller->getCommand($request);
        $cmd->execute($request);
    }
}
