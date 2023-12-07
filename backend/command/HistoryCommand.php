<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Command
 * @package  HistoryCommand
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Command;

use Libraries\Controllers\CommandController;
use Libraries\DataMapper\ModelMapper;
use Models\History;

class HistoryCommand extends CommandController
{

    /**
     * Mapper for this
     *
     * @var ModelMapper $historyMapper
     */
    private ModelMapper $historyMapper;

    /**
     * Init function.
     * It do set mapper for history model.
     *
     * @return void
     */
    protected function init(): void
    {
        $this->historyMapper = new History();
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
        $allHistory = $this->historyMapper->select();
        $history    = [];

        /*
        TODO:
          Will do refactoring Collection class, need get array without foreach.
        */
        foreach ($allHistory->getGenerator() as $objHistory) {
            $history[] = $objHistory->getRow();
        }

        $data = [
            'success' => true,
            'history' => $history,
        ];

        $this->apiResponce($data);
    }
}
