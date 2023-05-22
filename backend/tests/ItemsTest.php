<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Tests
 * @package  ItemsTest
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Tests;

use Command\ItemsCommand;
use Libraries\ApplicationHelper;
use Libraries\Registry;
use Libraries\Requests\ApiRequest;
use PHPUnit\Framework\TestCase;

/**
 * ItemsTest class
 * Description
 *
 * @category Tests
 * @package  ItemsTest
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class ItemsTest extends TestCase
{
    /**
     * Description
     *
     * @return void
     */
    public function testItemSelect(): void
    {
        $reg = Registry::instance();

        /* Select items */
        $reg->setRequestMethod('GET');
        $reg->setRequestUri('/api/items/');
        // Set PDO
        $appHelper = new ApplicationHelper();
        $refClass  = new \ReflectionClass($appHelper);
        $refMethod = $refClass->getMethod('setupOptions');
        $refMethod->invoke($appHelper);
        // Set Request
        $request = new ApiRequest();
        $reg->setRequest($request);
        // Invoke api method
        $items = new ItemsCommand();
        ob_start();
        $items->apiSelect();
        $json = ob_get_clean();
        // Check result
        $data = json_decode($json);
        $this->assertSame($data->success, true);
    }
}
