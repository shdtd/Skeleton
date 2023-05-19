<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Command
 * @package  ItemsCommand
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Command;

use Libraries\Controllers\CommandController;
use Libraries\DataMapper\ModelMapper;
use Models\Items;

/**
 * ItemsCommand class
 * Description
 *
 * @category Command
 * @package  ItemsCommand
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class ItemsCommand extends CommandController
{

    /**
     * Mapper for this
     *
     * @var ModelMapper $itemsMapper
     */
    private ModelMapper $itemsMapper;

    /**
     * Init function.
     * It do set mapper for items model.
     *
     * @return void
     */
    protected function init(): void
    {
        $this->itemsMapper = new Items();
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
        $allItems = $this->itemsMapper->select();
        $items    = [];

        /*
        TODO:
          Will do refactoring Collection class, need get array without foreach.
        */
        foreach ($allItems->getGenerator() as $objItems) {
            $items[] = $objItems->getRow();
        }

        $data = [
            'success' => true,
            'items'   => $items,
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
        $id   = (int) $this->params->get('id');
        $item = $this->itemsMapper->findByID($id);
        if ($item === null) {
            $data = [
                'success' => false,
                'message' => 'Not found.',
            ];
        } else {
            $data = [
                'success' => true,
                'items'   => $item->getRow(),
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
        $data = $this->validate();
        if ($data['success'] === false) {
            $this->apiResponce($data);
            return;
        }

        $row          = array_fill_keys($this->itemsMapper->getColumns(), null);
        $row['name']  = ($this->params->get('name') ?? null);
        $row['phone'] = ($this->params->get('phone') ?? null);
        $row['key']   = ($this->params->get('key') ?? null);

        if ($row['key'] !== null) {
            $obj     = $this->itemsMapper->createObject($row);
            $success = $this->itemsMapper->insert($obj);
            $data    = ['success' => $success];
        } else {
            $data = [
                'success' => false,
                'message' => "The field KEY is required, but it's not filled.",
            ];
        }

        $this->apiResponce($data);
    }

    /**
     * 'Update' function is update one row in model.
     *
     * @return void
     */
    public function apiUpdate(): void
    {
        $data = $this->validate();
        if ($data['success'] === false) {
            $this->apiResponce($data);
            return;
        }

        $row          = array_fill_keys($this->itemsMapper->getColumns(), null);
        $row['id']    = (int) ($this->params->get('id') ?? -1);
        $row['name']  = ($this->params->get('name') ?? null);
        $row['phone'] = ($this->params->get('phone') ?? null);
        $row['key']   = ($this->params->get('key') ?? null);

        if ($row['id'] !== -1 && $row['key'] !== null) {
            $obj     = $this->itemsMapper->createObject($row);
            $success = $this->itemsMapper->update($obj);
            $data    = ['success' => $success];
        } else {
            $data = [
                'success' => false,
                'message' => 'The fields ID and KEY are required, but they are not filled.',
            ];
        }

        $this->apiResponce($data);
    }

    /**
     * 'Delete' function is delete one row from model.
     *
     * @return void
     */
    public function apiDelete(): void
    {
        $id = (int) $this->params->get('id');
        if (empty($id) !== true) {
            $success = $this->itemsMapper->delete($id);
            $data    = ['success' => $success];
        } else {
            $data = [
                'success' => false,
                'message' => "The field ID is required, but it's not filled.",
            ];
        }

        $this->apiResponce($data);
    }

    /**
     * 'validate' 'validate' function is validate all field
     * before sending them in to database.
     *
     * @return array<string,string|boolean>
     */
    protected function validate(): array
    {
        $msg     = [];
        $success = true;
        
        $isName = preg_match(
            '/(^[[:alpha:]]+.*$)|(^$)/ui',
            (string) $this->params->get('name')
        );

        $isPhone = preg_match(
            '/(^\+{1}\d{10,14}$)|(^$)/',
            (string) $this->params->get('phone')
        );

        if ($isName !== 1) {
            $msg[]   = 'Name must start with a letter.';
            $success = false;
        }

        if ($isPhone !== 1) {
            $msg[]   = 'Phone format is +78623549813.';
            $success = false;
        }

        if (empty($this->params->get('key')) === true) {
            $msg[]   = 'Key cannot be empty.';
            $success = false;
        }

        return [
            'success' => $success,
            'message' => implode("\n", $msg),
        ];
    }
}
