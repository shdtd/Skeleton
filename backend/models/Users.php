<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Models
 * @package  Users
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Models;

use Libraries\DataMapper\Model;
use Libraries\DataMapper\ModelMapper;

class Users extends ModelMapper
{
    /**
     * Init function sets values for generete STMT
     *
     * @return void
     */
    protected function init(): void
    {
        $this->tableName  = 'users';
        $this->primaryKey = 'id';

        $this->columns = [
            'id',
            'firstname',
            'lastname',
            'email',
            'password',
            'created_at',
            'updated_at',
        ];

        $this->columnsProtected = [
            'id',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * The doLogin function check email and password.
     *
     * @param string $email    Email address.
     * @param string $password User password.
     *
     * @return Model|false
     */
    public function doLogin(string $email, string $password): Model|false
    {
        $checkEmailStmt = $this->pdo->prepare(
            'SELECT * FROM ' . $this->tableName . ' WHERE "email"=?'
        );

        $checkEmailStmt->execute([$email]);
        $rowCount = $checkEmailStmt->rowCount();

        if ($rowCount === 1) {
            $row = $checkEmailStmt->fetch(\PDO::FETCH_ASSOC);
            $checkEmailStmt->closeCursor();
            if (password_verify($password, $row['password']) === true) {
                return $this->createObject(
                    [
                        'id'        => $row['id'],
                        'firstname' => $row['firstname'],
                        'lastname'  => $row['lastname'],
                        'email'     => $row['email'],
                    ]
                );
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
