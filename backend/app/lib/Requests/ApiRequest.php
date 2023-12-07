<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Requests
 * @package  ApiRequest
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries\Requests;

class ApiRequest extends Request
{
    /**
     * Description
     *
     * @return void
     */
    public function init(): void
    {
        $this->prefix = '/api';
        $this->type   = 'api';
        $this->prepareCommands('api.php');
    }
}
