<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Routes
 * @package  Web
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Routes;

use Command\ArticleCommand;
use Command\HistoryCommand;
use Libraries\Facades\Route;
use Command\IndexCommand;
use Command\ItemsCommand;
use Command\NewsCommand;
use Command\UsersCommand;

// Route::group('articles', function() {
//     Route::get('/', ArticlesCommand::class, 'articles');
//     Route::get('top', ArticlesCommand::class, 'articles_top');
// });

Route::get('/', IndexCommand::class, 'index');
Route::get('/<one>/<two>/<three>/', IndexCommand::class, 'index');
Route::get('/none/', IndexCommand::class, 'index');
Route::get('/none/<index>/', IndexCommand::class, 'index');
Route::get('/none/<index>/<page>/', IndexCommand::class, 'index');
Route::get('/none/podvoh/<id>/', IndexCommand::class, 'index');
Route::get('/user/<name>/<fname>/', IndexCommand::class, 'index');
Route::get('/animal/<type>/', IndexCommand::class, 'index');
Route::get('/animal/fish/', IndexCommand::class, 'index');

/* Routing table for a Article model */
Route::group(
    'users',
    function () {
        Route::get('/', UsersCommand::class, 'index', 'JWT');
    }
);

/* Routing table for a Article model */
Route::group(
    'articles',
    function () {
        Route::get('/', ArticleCommand::class, 'index');
    }
);

/* Routing table for a News model */
Route::group(
    'news',
    function () {
        Route::get('/', NewsCommand::class, 'index');
    }
);

/* Routing table for a Items model */
Route::group(
    'items',
    function () {
        Route::get('/', ItemsCommand::class, 'index', 'JWT');
    }
);

/* Routing table for a History model */
Route::group(
    'history',
    function () {
        Route::get('/', HistoryCommand::class, 'index', 'JWT');
    }
);
