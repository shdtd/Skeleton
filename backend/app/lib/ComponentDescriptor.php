<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Libraries
 * @package  ComponentDescriptor
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries;

use Libraries\Controllers\CommandController;
use Libraries\Errors\AppException;

class ComponentDescriptor
{

    /**
     * Description
     *
     * @var object $refcmd
     */
    private static object $refcmd;

    /**
     * Description
     *
     * @var string $command
     */
    private string $command;

    /**
     * Description
     *
     * @var string $action
     */
    private string $action;

    /**
     * Constructor
     *
     * @param string $command Description.
     * @param string $action  Description.
     */
    public function __construct(string $command, string $action)
    {
        self::$refcmd  = new \ReflectionClass(CommandController::class);
        $this->command = $command;
        $this->action  = $action;
    }

    /**
     * Description
     *
     * @return CommandController
     */
    public function getCommand(): CommandController
    {
        return $this->resolveCommand($this->command);
    }

    /**
     * Description
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Description
     *
     * @param string $class Description.
     *
     * @throws AppException Unknown class.
     * @throws AppException Class not found.
     * @throws AppException Command does not belong to the Command class.
     * @return CommandController
     */
    private function resolveCommand(string $class): CommandController
    {
        if (empty($class) === true) {
            throw new AppException('Unknown class ' . $class . '!');
        }

        if (class_exists($class) === false) {
            throw new AppException('Class ' . $class . ' not found!');
        }

        $refclass = new \ReflectionClass($class);
        if ($refclass->isSubclassOf(self::$refcmd) === false) {
            throw new AppException(
                'Command ' . $class . ' does not belong to the Command class!'
            );
        }
        
        return (object) $refclass->newInstance();
    }
}
