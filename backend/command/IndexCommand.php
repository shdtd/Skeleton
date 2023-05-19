<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Command
 * @package  IndexCommand
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Command;

use Libraries\Controllers\CommandController;
use Models\Article;
use Models\News;

/**
 * IndexCommand class
 * Description
 *
 * @category Command
 * @package  IndexCommand
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class IndexCommand extends CommandController
{

    /**
     * Description
     *
     * @return void
     */
    protected function init(): void
    {
        // INIT
    }

    /**
     * Description
     *
     * @return void
     */
    public function webIndex(): void
    {
        /* For the only one React page */
        $data = [
            'file' => $this->reg->getAppPath() . '/public/index.html',
        ];

        $this->webResponce($data, 'index.tpl');
    }

    /**
     * Description
     *
     * @return void
     */
    public function apiIndex(): void
    {
        $newsMapper    = new News();
        $articleMapper = new Article();
        $allNews       = $newsMapper->select();
        $news          = [];
        $allArticle    = $articleMapper->select();
        $articles      = [];

        foreach ($allNews->getGenerator() as $objNews) {
            $news[] = $objNews->getRow();
        }

        foreach ($allArticle->getGenerator() as $objArticle) {
            $articles[] = $objArticle->getRow();
        }

        $data = [
            'success' => true,
            'news'    => $news,
            'article' => $articles,

        ];

        $this->apiResponce($data);
    }

    /**
     * Description
     *
     * @return void
     */
    public function apiCreate(): void
    {
        $data = [
            'success' => true,
            'message' => 'apiCreate',
        ];

        $this->apiResponce($data);
    }

    /**
     * Description
     *
     * @return void
     */
    public function apiTwo(): void
    {
        $data = [
            'success' => true,
            'message' => 'apiTwo',
            'params'  => $this->params->get('index'),
        ];

        $this->apiResponce($data);
    }

    /**
     * Description
     *
     * @return void
     */
    public function apiThree(): void
    {
        $data = [
            'success' => true,
            'message' => 'apiThree',
            'index'   => $this->params->get('index'),
            'page'    => $this->params->get('page'),
        ];

        $this->apiResponce($data);
    }

    /**
     * Description
     *
     * @return void
     */
    public function apiPodvoh(): void
    {
        $data = [
            'success' => true,
            'message' => 'apiPodvoh',
            'id'      => $this->params->get('id'),
        ];

        $this->apiResponce($data);
    }

    /**
     * Description
     *
     * @return void
     */
    public function apiQuery(): void
    {
        $data = [
            'success' => true,
            'message' => 'apiPodvoh',
            'one'     => $this->params->get('one'),
            'two'     => $this->params->get('two'),
            'three'   => $this->params->get('three'),
        ];

        $this->apiResponce($data);
    }

    /**
     * Description
     *
     * @return void
     */
    public function apiFish(): void
    {
        $data = [
            'success' => true,
            'message' => 'fish',
        ];

        $this->apiResponce($data);
    }

    /**
     * Description
     *
     * @return void
     */
    public function apiAnimal(): void
    {
        $data = [
            'success' => true,
            'message' => 'apiAnimal',
            'type'    => $this->params->get('type'),
        ];

        $this->apiResponce($data);
    }
}
