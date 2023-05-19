<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Models
 * @package  News
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Models;

use Libraries\DataMapper\ModelMapper;

/**
 * News class
 * Description
 *
 * @category Models
 * @package  News
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class News extends ModelMapper
{
    /**
     * Init function sets values for generete STMT
     *
     * @return void
     */
    protected function init(): void
    {
        $this->tableName  = 'news';
        $this->primaryKey = 'id';

        $this->columns = [
            'id',
            'header',
            'short_text',
            'text',
            'news_img',
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
