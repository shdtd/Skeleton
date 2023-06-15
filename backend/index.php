<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Skeleton
 * @package  Index.php
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Libraries\Controllers\Controller;
use Libraries\Registry;

$start  = microtime(true);
$memory = memory_get_usage();

Controller::run();

if (Registry::instance()->getConfig()->get('debug') === 'NEVER') {
    echo "\n\n<XMP>\n";
    echo "+--------------+---------------------------+\n";
    echo '| Lead time    | ' .
        sprintf('%-25s', (microtime(true) - $start) . ' second.') . " |\n";
    echo '| Memory usage | ' .
        sprintf('%-25s', (memory_get_usage() - $memory). ' byte.') . " |\n";
    echo "+--------------+---------------------------+\n";
    echo "\n</XMP>\n";
}
