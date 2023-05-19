<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Tests
 * @package  UriTest
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Tests;

use Libraries\Registry;
use Libraries\Requests\ApiRequest;
use PHPUnit\Framework\TestCase;

/**
 * UriTest class
 * Description
 *
 * @category Tests
 * @package  UriTest
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class UriTest extends TestCase
{
    /**
     * Description
     *
     * @return void
     */
    public function testClassConstructor(): void
    {
        Registry::instance()->setRequestMethod('GET');
        Registry::instance()->setRequestUri('/api/none/podvoh/a/');

        $request = new ApiRequest();

        $this->assertSame('/api/none/podvoh/a/', $request->getPath());
    }
}
