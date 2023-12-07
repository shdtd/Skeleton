<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category DataMapper
 * @package  ModelCollection
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries\DataMapper;

class ModelCollection
{

    /**
     * Description
     *
     * @var ModelMapper $mapper
     */
    protected ModelMapper $mapper;

    /**
     * Description
     *
     * @var integer $total
     */
    protected int $total = 0;

    /**
     * Description
     *
     * @var array<int,array<string,int|string>> $raw
     */
    protected array $raw = [];

    /**
     * Description
     *
     * @var array<int,object> $objects
     */
    private array $objects = [];

    /**
     * Description
     *
     * @param ModelMapper                         $mapper Description.
     * @param array<int,array<string,int|string>> $raw    Description.
     */
    public function __construct(ModelMapper $mapper, array $raw = [])
    {
        $this->raw    = $raw;
        $this->total  = count($raw);
        $this->mapper = $mapper;
    }

    /**
     * Description
     *
     * @param Model $object Description.
     *
     * @return void
     */
    public function add(Model $object): void
    {
        $this->notifyAccess();
        $this->objects[$this->total] = $object;
        $this->total++;
    }

    /**
     * Description
     *
     * @return \Generator
     */
    public function getGenerator(): \Generator
    {
        for ($i = 0; $i < $this->total; $i++) {
            yield $this->getRow($i);
        }
    }

    /**
     * Description
     *
     * @return void
     */
    protected function notifyAccess(): void
    {
        // Specifically left blank
    }

    /**
     * Description
     *
     * @param integer $num Description.
     *
     * @return Model|null
     */
    private function getRow(int $num): Model|null
    {
        $this->notifyAccess();

        if ($num >= $this->total || $num < 0) {
            return null;
        }

        if (isset($this->objects[$num]) === true) {
            return $this->objects[$num];
        }

        if (isset($this->raw[$num]) === true) {
            $this->objects[$num] = $this->mapper->createObject(
                $this->raw[$num]
            );
        }

        return $this->objects[$num];
    }
}
