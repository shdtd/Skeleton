<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category DataMapper
 * @package  ModelWatcher
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries\DataMapper;

class ModelWatcher
{

    /**
     * Description
     *
     * @var array<string,Model> $all
     */
    private array $all = [];

    /**
     * Description
     *
     * @var array<string,Model> $dirty
     */
    private array $dirty = [];

    /**
     * Description
     *
     * @var array<string,Model> $new
     */
    private array $new = [];

    /**
     * Description
     *
     * @var array<string,Model> $delete
     */
    private array $delete = [];

    /**
     * Description
     *
     * @var ModelWatcher $instance
     */
    private static ModelWatcher $instance;

    /**
     * Constructor is private
     */
    private function __construct()
    {
    }

    /**
     * Description
     *
     * @return self
     */
    public static function instance(): ModelWatcher
    {
        if (isset(self::$instance) === false) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Description
     *
     * @param Model $obj Description.
     *
     * @return string
     */
    public function globalKey(Model $obj): string
    {
        $key = get_class($obj) . '.' . $obj->get('id');
        return $key;
    }

    /**
     * Description
     *
     * @param Model $obj Description.
     *
     * @return void
     */
    public static function add(Model $obj): void
    {
        $inst = self::instance();
        $inst->all[$inst->globalKey($obj)] = $obj;
    }

    /**
     * Description
     *
     * @param string  $classname Description.
     * @param integer $id        Description.
     *
     * @return Model|null
     */
    public static function exists(string $classname, int $id): Model|null
    {
        $inst = self::instance();
        $key  = $classname . '.' . $id;

        if (isset($inst->all[$key]) === true) {
            return $inst->all[$key];
        }

        return null;
    }

    /**
     * Description
     *
     * @param Model $obj Description.
     *
     * @return void
     */
    public static function addDelete(Model $obj): void
    {
        $inst = self::instance();
        $inst->delete[$inst->globalKey($obj)] = $obj;
    }

    /**
     * Description
     *
     * @param Model $obj Description.
     *
     * @return void
     */
    public static function addDirty(Model $obj): void
    {
        $inst = self::instance();

        if (in_array($obj, $inst->new, true) === false) {
            $inst->dirty[$inst->globalKey($obj)] = $obj;
        }
    }

    /**
     * Description
     *
     * @param Model $obj Description.
     *
     * @return void
     */
    public static function addNew(Model $obj): void
    {
        $inst        = self::instance();
        $inst->new[] = $obj;
    }

    /**
     * Description
     *
     * @param Model $obj Description.
     *
     * @return void
     */
    public static function addClean(Model $obj): void
    {
        $inst = self::instance();
        unset($inst->delete[$inst->globalKey($obj)]);
        unset($inst->dirty[$inst->globalKey($obj)]);

        $inst->new = array_filter(
            $inst->new,
            function ($a) use ($obj) {
                return !($a === $obj);
            }
        );
    }

    /* public function performOperations(): void
    {
        foreach ($this->dirty as $key => $obj) {
            $obj->getFinder()->update($obj);
        }

        foreach ($this->new as $key => $obj) {
            $obj->getFinder()->insert($obj);
            print "Insert " . $obj->getName() . "\n";
        }

        $this->dirty = [];
        $this->new = [];
    } */
}
