<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category DataMapper
 * @package  ModelModules
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries\DataMapper;

trait ModelModules
{
    /**
     * Description
     *
     * @param string $email Description.
     *
     * @return boolean
     */
    public function uniqueEmail(string $email): bool
    {
        $checkEmailStmt = $this->pdo->prepare(
            'SELECT "email" FROM ' . $this->tableName . ' WHERE "email"=?'
        );

        $checkEmailStmt->execute([$email]);
        $rowCount = $checkEmailStmt->rowCount();

        if ($rowCount > 0) {
            return false;
        }

        return true;
    }
}
