<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category DataMapper
 * @package  Migration
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd
 */

declare(strict_types=1);

namespace Libraries\DataMapper;

use Libraries\Registry;

/**
 * Migration class
 * Description
 *
 * @category DataMapper
 * @package  Migration
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd
 */
abstract class Migration
{
    /**
     * PDO for MySql
     * 
     * @var \PDO $pdo
     */
    protected \PDO $pdo;

    /**
     * Array for create migration
     * 
     * @var array<int,\PDOStatement> $migration
     */
    protected array $migration = [];

    /**
     * Array for drop migration
     * 
     * @var array<int,\PDOStatement> $drrevert_migrationop
     */
    protected array $revert_migration = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pdo = Registry::instance()->getPdo();
        $this->init();
    }

    /**
     * In client code, SQL migrations are defined here
     * 
     * @return void
     */
    abstract protected function init(): void;

    /**
     * Execute migrations
     * 
     * @return void
     */
    public function migration(): void
    {
        foreach ($this->migration as $migration) {
            $migration->execute();
        }
    }

    /**
     * Execute revert migrations
     * 
     * @return void
     */
    public function revertMigration(): void
    {
        foreach ($this->revert_migration as $revert) {
            $revert->execute();
        }
    }
}