<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Libraries
 * @package  Config
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries;

/**
 * Config class
 * Description
 *
 * @category Libraries
 * @package  Config
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class Config
{

    /**
     * Description
     *
     * @var array<string,mixed> $options
     */
    private $options = [];

    /**
     * Constructor
     *
     * @param array<string,mixed> $opt Description.
     */
    public function __construct(array $opt = [])
    {
        $this->options = $opt;
    }

    /**
     * Description
     *
     * @param string $key Description.
     *
     * @return mixed
     */
    public function get(string $key): mixed
    {
        if (array_key_exists($key, $this->options) === false) {
            Registry::instance()->getRequest()->addFeedback(
                'Warning: Option ' . $key . ' not found.'
            );
            return null;
        }

        return $this->options[$key];
    }

    /**
     * Description
     *
     * @param string $key   Description.
     * @param mixed  $value Description.
     *
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->options[$key] = $value;
    }
}
