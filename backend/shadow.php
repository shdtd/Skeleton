#!/usr/bin/env php
<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Command line tool for Skeleton
 *
 * @category Console tool
 * @package  Shadow
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd
 */

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Libraries\Controllers\Controller;
use Libraries\DataMapper\Migration;
use Libraries\Registry;

if (empty($argv[1]) === true) {
    $command = 'help';
} else {
    $command = $argv[1];
}

switch($command) {
    case 'migration':
        Controller::run(argv: $argv);
        migrations($argv);
        break;
    case 'help':
    default:
        printf(
            "%s\n\t%s\n\n%s\n\t%s\n\t%s\n\n%s\n",
            'This tool is used as:',
            '`shadow command options`',
            'List of available commands:',
            'migration - run migrations',
            'help      - this text',
            'You can run `shadow command help` to get help on options for a particular command'
        );
        break;
}

/**
 * The check and run new or revert migrations
 * 
 * @param array<int,string> $argv
 * 
 * @return void
 */
function migrations($argv): void
{
    if (isset($argv[2]) === true) {
        /* use one file */
        if ($argv[2] !== 'revert' && $argv[2] !== 'help') {
            if (file_exists('migrations/' . $argv[2]) === false) {
                echo "File doesn't exist\n";
                return;
            }

            include_once('migrations/' . $argv[2]);
            $classname = preg_replace('/[0-9_]+|\.php{1}/', '', $argv[2]);
            $classMigration = new $classname();

            if (isset($argv[3]) === false) {
                /* It's migration */
                addMigration($classMigration, $argv[2], $classname);
            } else if ($argv[3] === 'revert') {
                /* It's revert migration */
                revertMigration($classMigration, $argv[2], $classname);
            }

            return;
        } else if ($argv[2] === 'help') {
            migration_help();
        }
    }

    $migrations = array_diff(scandir('migrations'), ['..', '.']);
    foreach($migrations as $migration) {
        $classname = preg_replace('/[0-9_]+|\.php{1}/', '', $migration);
        include_once('migrations/' . $migration);
        $classMigration = new $classname();
        if (isset($argv[2]) === false) {
            /* It's migration */
            addMigration($classMigration, $migration, $classname);
        } else if ($argv[2] === 'revert') {
            /* It's revert migration */
            revertMigration($classMigration, $migration, $classname);
        }
    }
}

function migration_help(): void
{
    printf(
        "%s\n%s\n",
        'use',
        '`shadow migration [filename] [revert]`'
    );
}

/**
 * The add migration to DB
 * The add record of migration in migrations table
 * 
 * @param Migration $classMigration
 * @param string    $filename
 * @param string    $classname
 * 
 * @return void
 */
function addMigration(Migration $classMigration, $filename, $classname): void
{
    $classMigration->migration();
    createMigrationTable();
    $pdo = Registry::instance()->getPdo();
    $addMigration = $pdo->prepare("
        INSERT INTO migrations (type, filename, classname)
        VALUES (0, '{$filename}', '{$classname}')
    ");
    $addMigration->execute();
}

/**
 * The revert migration from DB
 * The add record of revert migration in migrations table
 * 
 * @param Migration $classMigration
 * @param string    $filename
 * @param string    $classname
 * 
 * @return void
 */
function revertMigration(Migration $classMigration, $filename, $classname): void
{
    $classMigration->revertMigration();
    createMigrationTable();
    $pdo = Registry::instance()->getPdo();
    $revertMigration = $pdo->prepare("
        INSERT INTO migrations (type, filename, classname)
        VALUES (1, '{$filename}', '{$classname}')
    ");
    $revertMigration->execute();
}

function createMigrationTable(): void
{
    if (migrationTableExists(Registry::instance()->getPdo()) === false) {
        $pdo = Registry::instance()->getPdo();
        $createTable = $pdo->prepare("
        CREATE TABLE IF NOT EXISTS migrations (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT comment 'PRIMARY KEY',
            type tinyint(1) UNSIGNED NOT NULL comment '0 - migration, 1 - revert migration',
            filename varchar(100) NOT NULL comment 'Migration file name',
            classname varchar(100) NOT NULL comment 'Migration class name',
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        )
        ENGINE=InnoDB
        COMMENT='The table contains all system migrations.'
        ");
        $createTable->execute();
    }
}

/**
 * Check if a table exists in the current database.
 *
 * @param PDO $pdo PDO instance connected to a database.
 * 
 * @return bool TRUE if table exists, FALSE if no table found.
 */
function migrationTableExists($pdo): bool
{
    // Try a select statement against the table
    // Run it in try-catch in case PDO is in ERRMODE_EXCEPTION.
    try {
        $result = $pdo->query('SELECT 1 FROM migrations LIMIT 1');
    } catch (Exception $e) {
        // We got an exception (table not found)
        return false;
    }

    // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
    return $result !== false;
}