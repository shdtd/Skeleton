<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Requests
 * @package  Uri
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries\Requests;

use Libraries\Config;
use Libraries\Errors\AppException;
use Libraries\Registry;

/**
 * Uri trait
 * Description
 *
 * @category Requests
 * @package  Uri
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
trait Uri
{

    /**
     * Description
     *
     * @var string $path
     */
    protected string $path = '/';

    /**
     * Description
     *
     * @var array<string, string> $parameters
     */
    protected array $parameters = [];

    /**
     * Description
     *
     * @throws AppException There cannot be more than one ["?" query] block.
     * @return void
     */
    protected function initURI(): void
    {
        $this->parseURI();
        $this->parseHeaders();
    }

    /**
     * Description
     *
     * @return void
     */
    protected function parseURI(): void
    {
        $this->path       = strtok(Registry::instance()->getRequestUri(), '?');
        $this->parameters = array_merge($this->parameters, $_REQUEST);
        $inputMixed       = file_get_contents('php://input');
        $input            = json_decode($inputMixed, true);

        if (gettype($input) === 'array') {
            $this->parameters = array_merge($this->parameters, $input);
        } else {
            parse_str(file_get_contents('php://input'), $inputUrlEncode);
            if (gettype($inputUrlEncode) === 'array') {
                $this->parameters = array_merge(
                    $this->parameters,
                    $inputUrlEncode
                );
            }
        }
    }

    /**
     * Description
     *
     * @return void
     */
    protected function parseHeaders(): void
    {
        if (isset($_SERVER['HTTP_AUTHORIZATION']) === true) {
            $this->parameters['Authorization'] = $_SERVER['HTTP_AUTHORIZATION'];
        }
    }

    /**
     * Description
     *
     * @return string
     */
    public function getPath(): string
    {
        if (empty($this->path) === true) {
            $this->path = strtok(Registry::instance()->getRequestUri(), '?');
        }

        return $this->path;
    }

    /**
     * Description
     *
     * @return Config
     */
    public function getParameters(): Config
    {
        return new Config($this->parameters);
    }

    /**
     * Description
     *
     * @param string $key   Description.
     * @param string $value Description.
     *
     * @return void
     */
    public function addParameters(string $key, string $value): void
    {
        $this->parameters[$key] = $value;
    }
}
