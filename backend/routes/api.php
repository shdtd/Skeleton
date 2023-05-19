<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Routes
 * @package  Api
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Routes;

use Command\ArticleCommand;
use Command\HistoryCommand;
use Command\IndexCommand;
use Command\ItemsCommand;
use Command\NewsCommand;
use Command\UsersCommand;
use Libraries\Facades\Route;
use Libraries\JWTAuth;

Route::group(
    'v1',
    function () {
        Route::get('/', IndexCommand::class, 'index');
        Route::post('data', IndexCommand::class, 'create');
    }
);

Route::get('/', IndexCommand::class, 'index');
Route::get('/<one>/<two>/<three>/', IndexCommand::class, 'query');
Route::get('/none/', IndexCommand::class, 'index');
Route::get('/none/<index>/', IndexCommand::class, 'two');
Route::get('/none/<index>/<page>/', IndexCommand::class, 'three');
Route::get('/none/podvoh/<id>/', IndexCommand::class, 'podvoh');
Route::get('/user/<name>/<fname>/', IndexCommand::class, 'index');
Route::get('/animal/<type>/', IndexCommand::class, 'animal');
Route::get('/animal/fish/', IndexCommand::class, 'fish');
/* TODO: Not work */
Route::get('/article/<id>/', ArticleCommand::class, 'findByID');
/* Routing table for a Articles model */
Route::group(
    'article',
    function () {
        Route::get('/', ArticleCommand::class, 'select');
        Route::post('/', ArticleCommand::class, 'create', 'JWT');
        Route::put('/', ArticleCommand::class, 'update', 'JWT');
        Route::delete('/', ArticleCommand::class, 'delete', 'JWT');
    }
);
/* Routing table for a News model */
Route::group(
    'news',
    function () {
        Route::get('/', NewsCommand::class, 'select');
        Route::post('/', NewsCommand::class, 'create', 'JWT');
        Route::put('/', NewsCommand::class, 'update', 'JWT');
        Route::delete('/', NewsCommand::class, 'delete', 'JWT');
    }
);
/* Routing table for a Items model */
Route::group(
    'items',
    function () {
        Route::get('/', ItemsCommand::class, 'select', 'JWT');
        Route::post('/', ItemsCommand::class, 'create', 'JWT');
        Route::put('/', ItemsCommand::class, 'update', 'JWT');
        Route::delete('/', ItemsCommand::class, 'delete', 'JWT');
    }
);
/* Routing table for a Users model */
Route::group(
    'users',
    function () {
        Route::get('/', UsersCommand::class, 'select', 'JWT');
        Route::post('/', UsersCommand::class, 'create', 'JWT');
        Route::put('/', UsersCommand::class, 'update', 'JWT');
        Route::delete('/', UsersCommand::class, 'delete', 'JWT');
        Route::post('/login/', UsersCommand::class, 'login');
    }
);
/* Routing table for a History model */
Route::get('/history/', HistoryCommand::class, 'select', 'JWT');
