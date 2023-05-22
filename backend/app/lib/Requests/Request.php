<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Requests
 * @package  Request
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries\Requests;

use Libraries\Registry;

/**
 * Request class
 * Description
 *
 * @category Requests
 * @package  Request
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
abstract class Request
{
    use Uri;

    /**
     * Description
     *
     * @var array<string> $feedback
     */
    protected array $feedback = [];

    /**
     * Description
     *
     * @var string $prefix
     */
    protected string $prefix = '';

    /**
     * Description
     *
     * @var string $type
     */
    protected string $type = 'web';

    /**
     * Constructor
     *
     * @param string $authorization Token for a PHPUnit tests.
     */
    public function __construct(string $authorization = null)
    {
        /* For a PHPUnit tests */
        if (isset($authorization) === true) {
            $this->parameters['Authorization'] = $authorization;
        }

        $reg = Registry::instance();
        $reg->setRequest($this);
        $this->initURI();
        $this->init();
    }

    /**
     * Description
     *
     * @return void
     */
    abstract public function init(): void;

    /**
     * Description
     *
     * @param string $routeFile Description.
     *
     * @return void
     */
    protected function prepareCommands(string $routeFile): void
    {
        $reg = Registry::instance();
        include_once $reg->getAppPath() . '/backend/routes/' . $routeFile;
        $commands = $reg->getRouteCollection()->getCommands(
            $reg->getRequestMethod()
        );
        $reg->setCommands($commands);
    }

    /**
     * Description
     *
     * @param string $msg Description.
     *
     * @return void
     */
    public function addFeedback(string $msg): void
    {
        array_push($this->feedback, $msg);
    }

    /**
     * Description
     *
     * @return array<string>
     */
    public function getFeedback(): array
    {
        return $this->feedback;
    }

    /**
     * Description
     *
     * @param string $separator Description.
     *
     * @return string
     */
    public function getFeedbackString(string $separator = "\n"): string
    {
        return implode($separator, $this->feedback);
    }

    /**
     * Description
     *
     * @return void
     */
    public function clearFeedback(): void
    {
        $this->feedback = [];
    }

    /**
     * Description
     *
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Description
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
