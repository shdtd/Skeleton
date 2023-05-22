<?php

/**
 * PHP version 8.2.5
 *
 * @file
 * Description
 *
 * @category Libraries
 * @package  JWTAuth
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Libraries\DataMapper\Model;

/**
 * JWTAuth class
 * Description
 *
 * @category Libraries
 * @package  JWTAuth
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class JWTAuth
{

    /**
     * Private RSA privateKey
     *
     * @var \OpenSSLAsymmetricKey $privateKey
     */
    private \OpenSSLAsymmetricKey $privateKey;

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
        $this->reg        = Registry::instance();
        $this->privateKey = openssl_pkey_get_private(
            file_get_contents(
                $this->reg->getAppPath() . '/backend/app/var/auth/jwtKey.pem'
            )
        );
    }

    /**
     * Description
     *
     * @param Model $user Description.
     *
     * @return string A signed JWT
     */
    public function encodeJWT(Model $user): string
    {
        $payload = [
            'iss'  => 'Skeleton',
            'aud'  => $user->get('email'),
            'iat'  => time(),
            'nbf'  => time(),
            'exp'  => strtotime('+1 day'),
            'data' => [
                'id'        => $user->get('id'),
                'firstname' => $user->get('firstname'),
                'lastname'  => $user->get('lastname'),
                'email'     => $user->get('email'),
            ],
        ];

        return JWT::encode($payload, $this->privateKey, 'RS256');
    }

    /**
     * Description
     *
     * @param string $jwt JSON Web Token.
     *
     * @return \stdClass|false
     */
    public function decodeJWT(string $jwt): \stdClass|false
    {
        $publicKey = openssl_pkey_get_details($this->privateKey)['key'];
        $key       = new Key($publicKey, 'RS256');

        try {
            $payload = JWT::decode($jwt, $key);
        } catch (Exception $e) {
            $this->reg->getRequest()->addFeedback($e->getMessage());
            return false;
        }

        return $payload;
    }

    /**
     * 'CheckToken' function is check a token.
     *
     * @return boolean
     */
    public function checkToken(): bool
    {
        $reg     = Registry::instance();
        $jwtAuth = new JWTAuth();
        $jwt     = $reg->getRequest()->getParameters()->get('Authorization');
        if (empty($jwt) === true) {
            return false;
        }

        $payload = $jwtAuth->decodeJWT($jwt);

        if (($payload instanceof \stdClass) === false) {
            return false;
        }

        $user = $reg->getUserMapper()->findByID($payload->data->id);

        if (($user instanceof Model) === false) {
            return false;
        }

        if ($payload->iss === 'Skeleton'
            && $payload->nbf < time()
            && $payload->exp > time()
            && $user->get('email') === $payload->aud
            && $user->get('email') === $payload->data->email
            && $user->get('firstname') === $payload->data->firstname
            && $user->get('lastname') === $payload->data->lastname
        ) {
                return true;
        }

        return false;
    }
}
