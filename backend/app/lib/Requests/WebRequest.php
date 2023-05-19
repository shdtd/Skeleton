<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Requests
 * @package  WebRequest
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries\Requests;

/**
 * WebRequest class
 * Description
 *
 * @category Requests
 * @package  WebRequest
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class WebRequest extends Request
{
    /**
     * Description
     *
     * @return void
     */
    public function init(): void
    {
        $this->prefix = '';
        $this->type   = 'web';
        $this->prepareCommands('web.php');
    }
}
