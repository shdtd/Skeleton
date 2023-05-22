<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Tests\DB
 * @package  DBExtension
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Tests\DB;

/**
 * DBExtension class
 * Description
 *
 * @category Tests\DB
 * @package  DBExtension
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class DBExtension
{

    /**
     * Constand for setTrigers function this's disable all trigers
     */
    const DISABLE_TRIGGERS = 'DISABLE';

    /**
     * Constand for setTrigers function this's enable all trigers
     */
    const ENABLE_TRIGGERS = 'ENABLE';

    /**
     * Description
     *
     * @var DBExtension $instance
     */
    private static DBExtension $instance;

    /**
     * Description
     *
     * @var \PDO $db
     */
    public readonly \PDO $db;

    /**
     * Private constructor
     */
    private function __construct()
    {
        $configFile = __DIR__ . '/../../app/var/options.ini';

        if (is_file($configFile) === false) {
            die('Config file "' . $configFile . '" does not exist.' . "\n");
        }

        $config   = parse_ini_file($configFile, true);
        $this->db = new \PDO(
            sprintf(
                '%s:host=%s;port=%s;dbname=%s;user=%s;password=%s',
                $config['database']['driver'],
                $config['database']['dbhost'],
                $config['database']['dbport'],
                $config['database']['dbname'],
                $config['database']['dbuser'],
                $config['database']['dbpass']
            )
        );

        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db->beginTransaction();
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->db->rollBack();
    }

    /**
     * Get link to object
     *
     * @return self
     */
    public static function getInstance(): DBExtension
    {
        if (isset(self::$instance) === false) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Set and display status all triggers
     *
     * @param string $action Action for set to all triggers.
     * @param string $table  Name of table.
     *
     * @return void
     */
    public function setTriggers(string $action, string $table): void
    {
        $this->db->exec(
            'ALTER TABLE ' . $table . ' ' . $action . ' TRIGGER ALL'
        );

        $triggers = $this->db->query(
            'SELECT pg_class.relname as dbname, pg_trigger.tgname, ' .
            "case pg_trigger.tgenabled when 'O' then 'Enabled' " .
            "when 'D' then 'Disabled' end FROM pg_trigger " .
            'JOIN pg_class ON pg_trigger.tgrelid = pg_class.oid ' .
            'JOIN pg_namespace ON pg_namespace.oid = pg_class.relnamespace'
        );

        $rows = $triggers->fetchAll(\PDO::FETCH_ASSOC);

        echo "\n";
        echo "+-------------+----------+----------+\n";
        echo "|   TRIGGER   | DATABASE |  STATUS  |\n";
        echo "+-------------+----------+----------+\n";
        foreach ($rows as $row) {
            printf(
                "| %11s | %-8s | %8s |\n",
                $row['tgname'],
                $row['dbname'],
                $row['case']
            );
        }
        echo "+-------------+----------+----------+\n";
        echo "\n";
    }
}
