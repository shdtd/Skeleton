<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Authorization of Session
 *
 * @category Libraries
 * @package  SessionAuth
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

 declare(strict_types=1);

 namespace Libraries;

use Libraries\DataMapper\Model;
use Models\Users;

/**
 * SessionAuth class
 * Authorization of Session
 *
 * @category Libraries
 * @package  SessionAuth
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class SessionAuth
{
    /**
     * Registry instance
     *
     * @var Registry $reg
     */
    private Registry $reg;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reg = Registry::instance();
        $this->init();
    }

    /**
     * Check and initialize session
     * 
     * @return void
     */
    protected function init()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            ini_set('session.use_strict_mode', 1);
            session_start();
        }

        $lifetime = $this->reg->getConfig()->get('auth_lifetime');
        if (empty($_SESSION['TIME_OF_DESTROY']) === false &&
            $_SESSION['TIME_OF_DESTROY'] < time() - $lifetime
        ) {
            $_SESSION = [];
        } else {
            $_SESSION['TIME_OF_DESTROY'] = time();
        }
    }

    /**
     * Check login
     * 
     * @return bool
     */
    public function checkLogin(string $login = '', string $password = ''): bool
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            $this->init();
        }
    
        if (isset($_SESSION['auth']) === false || $_SESSION['auth'] === false) {
            if ($login === '' || $password === '') {
                if (isset($_SESSION['auth']) === false) {
                    $_SESSION['auth'] = false;
                }

                return $_SESSION['auth'];
            }

            $users = $this->reg->getUserMapper();
            $user = $users->doLogin($login, $password);

            if (is_array($user) === false) {
                $this->reg->setUserData([]);
                $_SESSION['auth'] = false;
            } else {
                $_SESSION['user'] = $user;
                $_SESSION['auth'] = true;
            }
        }

        if (isset($_SESSION['user']) === true) {
            $this->reg->setUserData($_SESSION['user']);
        }

        return $_SESSION['auth'];
    }

    /**
     * LogOut function
     * 
     * @return void
     */
    public function logOut(): void
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            $this->init();
        }

        $_SESSION = [];
    }
}
