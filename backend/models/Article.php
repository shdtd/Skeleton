<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Models
 * @package  Article
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Models;

use Libraries\DataMapper\ModelMapper;

/**
 * Article class
 * Description
 *
 * @category Models
 * @package  Article
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class Article extends ModelMapper
{
    /**
     * Init function sets values for generete STMT
     *
     * @return void
     */
    protected function init(): void
    {
        $this->tableName  = 'articles';
        $this->primaryKey = 'id';

        $this->columns = [
            'id',
            'header_text',
            'header_img',
            'small_text',
            'full_text',
            'article_img',
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
