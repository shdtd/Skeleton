<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Command
 * @package  ArticleCommand
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Command;

use Libraries\Controllers\CommandController;
use Models\Article;

class ArticleCommand extends CommandController
{

    /**
     * Mapper for this
     *
     * @var Article $dataMapper
     */
    private Article $dataMapper;

    /**
     * Init function.
     * It do set mapper for items model.
     *
     * @return void
     */
    protected function init(): void
    {
        $this->dataMapper = new Article();
    }

    /**
     * Web interface function, name is first 'web'.
     * In to routing table use name is last only.
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
     * API interface function, name is first 'api'.
     * In to routing table use name is last only.
     * 'Select' function is get all rows from model.
     *
     * @return void
     */
    public function apiSelect(): void
    {
        $collection = $this->dataMapper->select();
        $rows       = [];

        /*
        TODO:
          Will do refactoring Collection class, need get array without foreach.
        */
        foreach ($collection->getGenerator() as $object) {
            $rows[] = $object->getRow();
        }

        $data = [
            'success'  => true,
            'articles' => $rows,
        ];

        $this->apiResponce($data);
    }

    /**
     * 'FindByID' function is get one row by ID from model.
     *
     * @return void
     */
    public function apiFindByID(): void
    {
        $id    = (int) $this->params->get('id');
        $model = $this->dataMapper->findByID($id);
        if ($model === null) {
            $data = [
                'success' => false,
                'message' => 'Not found.',
            ];
        } else {
            $data = [
                'success'  => true,
                'articles' => $model->getRow(),
            ];
        }

        $this->apiResponce($data);
    }

    /**
     * 'Create' function is create new row in model.
     *
     * @return void
     */
    public function apiCreate(): void
    {
        $row = [];
    
        foreach ($this->dataMapper->getColumns(
            $this->dataMapper::PUBLIC_COLUMN
        ) as $column) {
            $row[$column] = $this->params->get($column);
        }

        $obj     = $this->dataMapper->createObject($row);
        $success = $this->dataMapper->insert($obj);
        $data    = ['success' => $success];

        $this->apiResponce($data);
    }

    /**
     * 'Update' function is update one row in model.
     *
     * @return void
     */
    public function apiUpdate(): void
    {
        $row = ['id' => (int) ($this->params->get('id') ?? -1)];
        
        if ($row['id'] === -1) {
            $data = [
                'success' => false,
                'message' => 'ID is NULL.',
            ];

            $this->apiResponce($data);
            return;
        }

        foreach ($this->dataMapper->getColumns(
            $this->dataMapper::PUBLIC_COLUMN
        ) as $column) {
            $row[$column] = ($this->params->get($column) ?? null);
        }

        $obj     = $this->dataMapper->createObject($row);
        $success = $this->dataMapper->update($obj);
        $data    = ['success' => $success];

        $this->apiResponce($data);
    }

    /**
     * 'Delete' function is delete one row from model.
     *
     * @return void
     */
    public function apiDelete(): void
    {
        $id = (int) ($this->params->get('id') ?? -1);

        if ($id === -1) {
            $data = [
                'success' => false,
                'message' => 'ID is NULL.',
            ];

            $this->apiResponce($data);
            return;
        }

        if (empty($id) !== true) {
            $success = $this->dataMapper->delete($id);
            $data    = ['success' => $success];
        } else {
            $data = [
                'success' => false,
                'message' => "The field ID is required, but it's not filled.",
            ];
        }

        $this->apiResponce($data);
    }
}
