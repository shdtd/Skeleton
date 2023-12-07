<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Command
 * @package  ErrorCommand
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Command;

use Libraries\Controllers\CommandController;

class ErrorCommand extends CommandController
{
    /**
     * Description
     *
     * @return void
     */
    public function web404(): void
    {
        $this->webResponce([], '404.tpl');
    }

    /**
     * Description
     *
     * @return void
     */
    protected function init(): void
    {
        // INIT
    }

    /**
     * Description
     *
     * @return void
     */
    public function api404(): void
    {
        $data = [
            'success' => false,
            'message' => 'Method not found',
        ];

        $this->apiResponce($data);
    }
}
