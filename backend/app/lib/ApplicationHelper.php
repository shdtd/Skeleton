<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Libraries
 * @package  ApplicationHelper
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries;

use Libraries\Errors\AppException;
use Libraries\Requests\ApiRequest;
use Libraries\Requests\WebRequest;

class ApplicationHelper
{

    /**
     * Description
     *
     * @var Registry $reg;
     */
    private Registry $reg;

    /**
     * Description
     *
     * @var string $config
     */
    private string $config;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reg    = Registry::instance();
        $this->config = $this->reg->getAppPath()
                            . '/backend/app/var/options.ini';
    }

    /**
     * The method sets the request mode
     *
     * @return void
     */
    public function init(): void
    {
        $this->setupOptions();

        if (preg_match('~(^/api$)|(^/api/)~', $this->reg->getRequestUri()) === 1) {
            $request = new ApiRequest();
        } else {
            $request = new WebRequest();
        }
    }

    /**
     * The method parses the options.ini file.
     *
     * @throws AppException Configuration file not found.
     * @return void
     */
    private function setupOptions(): void
    {
        /* Checking for the existence of the options.ini file */
        if (file_exists($this->config) === false) {
            throw new AppException(
                'Configuration file (' . $this->config . ') not found!'
            );
        }

        $options = parse_ini_file($this->config, true);
        /* Parsing the [config] section */
        $config = new Config($options['config']);
        $this->reg->setConfig($config);
        /* Parsing the [database] section */
        $database = new Config($options['database']);
        $dsn      = sprintf(
            '%s:host=%s;port=%s;dbname=%s;user=%s;password=%s',
            $database->get('driver'),
            $database->get('dbhost'),
            $database->get('dbport'),
            $database->get('dbname'),
            $database->get('dbuser'),
            $database->get('dbpass')
        );
        $pdo      = new \PDO($dsn);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->reg->setPdo($pdo);
    }
}
