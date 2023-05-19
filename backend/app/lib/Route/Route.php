<?php declare(strict_types=1);

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category AppRoute
 * @package  Route
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

namespace Libraries\Route;

/**
 * Route class
 * Description
 *
 * @category AppRoute
 * @package  Route
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class Route
{

    /**
     * Description
     *
     * @var array<string> $pathParts
     */
    protected array $pathParts = [];

    /**
     * Description
     *
     * @var array<string> $pathParams
     */
    protected array $pathParams = [];

    /**
     * Description
     *
     * @var integer $partsCount
     */
    protected int $partsCount = 0;

    /**
     * Function description
     *
     * @param string $method  HTTP method.
     * @param string $path    HTTP URL path.
     * @param string $command Command class name.
     * @param string $action  Name method of command class.
     * @param string $lock    Auth method.
     */
    public function __construct(
        protected string $method,
        protected string $path,
        protected string $command,
        protected string $action,
        protected string $lock
    ) {
        $this->init();
    }

    /**
     * Function description
     *
     * @return void
     */
    protected function init(): void
    {
        $parts = explode('/', $this->path);
        $parts = array_diff($parts, ['']);

        foreach ($parts as $part) {
            if ($part[0] === '<' && $part[(strlen($part) - 1)] === '>') {
                $this->pathParams[] = substr($part, 1, -1);
            } else {
                $this->pathParts[] = $part;
            }
            $this->partsCount++;
        }
    }

    /**
     * Function description
     *
     * @return array<string>
     */
    public function getParts(): array
    {
        return $this->pathParts;
    }

    /**
     * Function description
     *
     * @return array<string>
     */
    public function getParameters(): array
    {
        return $this->pathParams;
    }

    /**
     * Function description
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Function description
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Function description
     *
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * Function description
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Function description
     *
     * @return integer
     */
    public function getCount(): int
    {
        return $this->partsCount;
    }
}
