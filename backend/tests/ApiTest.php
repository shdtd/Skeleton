<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Tests
 * @package  ApiTest
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Tests;

use Libraries\Controllers\Controller;
use Libraries\Registry;
use Libraries\Requests\ApiRequest;
use PHPUnit\Framework\TestCase;

/**
 * ApiTest class
 * Description
 *
 * @category Tests
 * @package  ApiTest
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class ApiTest extends TestCase
{

    /**
     * Description
     *
     * @return void
     */
    public function testAppOpenApi(): void
    {
        $reg = Registry::instance();

        /* Select news */
        $reg->setRequestMethod('GET');
        $reg->setRequestUri('/api/news/');
        $request = new ApiRequest();
        $reg->setRequest($request);
        ob_start();
        Controller::run($reg->getRequestMethod(), $reg->getRequestUri());
        $json = ob_get_clean();
        $data = json_decode($json);
        $this->assertSame($data->success, true);

        /* Select articles */
        $reg->setRequestMethod('GET');
        $reg->setRequestUri('/api/articles/');
        $request = new ApiRequest();
        $reg->setRequest($request);
        ob_start();
        Controller::run($reg->getRequestMethod(), $reg->getRequestUri());
        $json = ob_get_clean();
        $data = json_decode($json);
        $this->assertSame($data->success, true);
    }
}
