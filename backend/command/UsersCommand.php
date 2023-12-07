<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Command
 * @package  UsersCommand
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Command;

use Libraries\Controllers\CommandController;
use Libraries\JWTAuth;
use Models\Users;

class UsersCommand extends CommandController
{

    /**
     * Mapper for this
     *
     * @var Users $usersMapper
     */
    private Users $usersMapper;

    /**
     * Init function.
     * It do set mapper for items model.
     *
     * @return void
     */
    protected function init(): void
    {
        $this->usersMapper = $this->reg->getUserMapper();
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
        $allUsers = $this->usersMapper->select();
        $users    = [];

        /*
        TODO:
          Will do refactoring Collection class, need get array without foreach.
        */
        foreach ($allUsers->getGenerator() as $objUsers) {
            $users[] = $objUsers->getRow();
        }

        $data = [
            'success' => true,
            'users'   => $users,
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
        $users = $this->usersMapper->findByID($id);
        if ($users === null) {
            $data = [
                'success' => false,
                'message' => 'Not found.',
            ];
        } else {
            $data = [
                'success' => true,
                'users'   => $users->getRow(),
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
        $row = array_fill_keys($this->usersMapper->getColumns(), null);
        $row['firstname'] = ($this->params->get('firstname') ?? null);
        $row['lastname']  = ($this->params->get('lastname') ?? null);
        $row['email']     = ($this->params->get('email') ?? null);
        $row['password']  = ($this->params->get('password') ?? null);

        if ($this->usersMapper->uniqueEmail($row['email']) === false) {
            $data = [
                'success' => false,
                'message' => 'The EMAIL not unique.',
            ];
        } else if ($row['email'] !== null && empty($row['password']) !== true) {
            $row['password'] = password_hash(
                $row['password'],
                PASSWORD_DEFAULT
            );
            $obj     = $this->usersMapper->createObject($row);
            $success = $this->usersMapper->insert($obj);
            $data    = ['success' => $success];
        } else {
            $data = [
                'success' => false,
                'message' => 'The field EMAIL and PASSWORD is required, but they are not filled.',
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
        $row       = array_fill_keys($this->usersMapper->getColumns(), null);
        $row['id'] = (int) ($this->params->get('id') ?? -1);
        $row['firstname'] = ($this->params->get('firstname') ?? null);
        $row['lastname']  = ($this->params->get('lastname') ?? null);
        $row['email']     = ($this->params->get('email') ?? null);
        $row['password']  = ($this->params->get('password') ?? null);

        if (empty($row['password']) === false) {
            $row['password'] = password_hash(
                $row['password'],
                PASSWORD_DEFAULT
            );
        } else {
            unset($row['password']);
        }

        if ($row['id'] !== -1 && $row['email'] !== null) {
            $obj = $this->usersMapper->createObject($row);

            if (isset($row['password']) === true) {
                $success = $this->usersMapper->update($obj);
            } else {
                $success = $this->usersMapper->updateWithoutPassword($obj);
            }

            if ($success === true) {
                $data = [
                    'success' => $success,
                    'message' => 'User data changed, re-login required',
                ];
            } else {
                $data = [
                    'success' => $success,
                    'message' => 'Unknown error',
                ];
            }
        } else {
            $data = [
                'success' => false,
                'message' => 'The fields ID and EMAIL are required, but they are not filled.',
            ];
        }//end if
        
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
            $success = $this->usersMapper->delete($id);
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
     * 'Login' function is login user and get token.
     *
     * @return void
     */
    public function apiLogin(): void
    {
        $email    = ($this->params->get('email') ?? '');
        $password = ($this->params->get('password') ?? '');
        $user     = $this->usersMapper->doLogin($email, $password);
        
        if ($user === false) {
            $data = [
                'success' => false,
                'message' => 'Authentication failed',
            ];

            $this->apiResponce($data);
            return;
        }

        $jwtAuth = new JWTAuth();
        $jwt     = $jwtAuth->encodeJWT($user);

        $data = [
            'success' => true,
            'message' => 'Access granted',
            'JWT'     => $jwt,
        ];
        $this->apiResponce($data);
    }
}
