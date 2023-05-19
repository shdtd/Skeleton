<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Models
 * @package  History
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Models;

use Libraries\DataMapper\ModelMapper;

/**
 * History class
 * Description
 *
 * @category Models
 * @package  History
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class History extends ModelMapper
{
    /**
     * Init function sets values for generete STMT
     *
     * @return void
     */
    protected function init(): void
    {
        $this->tableName      = 'history';
        $this->primaryKey     = 'id';
        $this->defaultOrderBy = 'tstamp';

        $this->columns = [
            'id',
            'tstamp',
            'schemaname',
            'tabname',
            'operation',
            'who',
            'new_val',
            'old_val',
        ];

        $this->columnsProtected = [
            'id',
            'tstamp',
            'schemaname',
            'tabname',
            'operation',
            'who',
            'new_val',
            'old_val',
        ];
    }
}
