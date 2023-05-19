<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Models
 * @package  Items
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Models;

use Libraries\DataMapper\ModelMapper;

/**
 * Items class
 * Description
 *
 * @category Models
 * @package  Items
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class Items extends ModelMapper
{
    /**
     * Init function sets values for generete STMT
     *
     * @return void
     */
    protected function init(): void
    {
        $this->tableName  = 'items';
        $this->primaryKey = 'id';

        $this->columns = [
            'id',
            'name',
            'phone',
            'key',
            'created_at',
            'updated_at',
        ];

        $this->columnsProtected = [
            'id',
            'created_at',
            'updated_at',
        ];
    }
}
