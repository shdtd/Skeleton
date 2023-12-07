<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category DataMapper
 * @package  Model
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries\DataMapper;

class Model
{

    /**
     * Description
     *
     * @var array<string,int|string> $model
     */
    private array $model = [];

    /**
     * Constructor
     *
     * @param array<string,int|string> $model Description.
     */
    public function __construct(array $model)
    {
        $this->model = $model;
    }

    /**
     * Description
     *
     * @param string $col Description.
     *
     * @return integer|string|false
     */
    public function get(string $col): int|string|false
    {
        return ($this->model[$col] ?? false);
    }

    /**
     * Description
     *
     * @param string         $col   Description.
     * @param integer|string $value Description.
     *
     * @return void
     */
    public function set(string $col, int|string $value): void
    {
        $this->model[$col] = $value;
        $this->markDirty();
    }

    /**
     * Description
     *
     * @return array<string,int|string>
     */
    public function getRow(): array
    {
        return $this->model;
    }

    /**
     * Description
     *
     * @return void
     */
    public function markDirty(): void
    {
    }
}
