<?php

namespace spartaksun\addresses\components;


use spartaksun\addresses\AddressBookException;

/**
 * Authentication of user
 * @package spartaksun\addresses\components
 */
class UserAuth
{

    const AUTH_SESSION_PERIOD   = 3600;
    const AUTH_SESSION_KEY      = 'auth';
    const CRYPT_SALT            = '~0We4Yu9.-)Hj6^7ghy\_SfQZx\90-==';

    private $_session;


    public function __construct()
    {
        $this->_session = Session::getInstance();
    }

    /**
     * Login - stores user identity in session
     * @param $username
     * @throws AddressBookException
     */
    public function login($username)
    {
        $this->_session->set(self::AUTH_SESSION_KEY, array(
            'username' => $username,
            'ip' => $this->getCryptIp(),
            'expire' => time() + self::AUTH_SESSION_PERIOD
        ));
    }

    /**
     * Log out user
     */
    public function logout()
    {
        $this->_session->delete(self::AUTH_SESSION_KEY);
    }

    /**
     * Receive username from session
     * @return mixed
     */
    public function getUserName()
    {
        $params = $this->_session->get(self::AUTH_SESSION_KEY);

        return $params['username'];
    }

    /**
     * Check is user authenticated
     * @return bool
     * @throws AddressBookException
     */
    public function isAuthenticate()
    {
        $params = $this->_session->get(self::AUTH_SESSION_KEY);
        if($params['ip'] !== $this->getCryptIp()) {
            return false;
        }
        if(time() > $params['expire']) {
            $this->logout();
            $this->_session->setFlash('Session has expired. Please log-in again.');

            return false;
        }

        return true;
    }

    /**
     * Hash for user IP address
     * @return string
     * @throws AddressBookException
     */
    private function getCryptIp()
    {
        if(empty($_SERVER['REMOTE_ADDR'])) {
            throw new AddressBookException('Sorry, we can not recognize your IP address.');
        }

        return self::hash($_SERVER['REMOTE_ADDR']);
    }

    /**
     * Hash of value
     * @param $value
     * @return string
     */
    public static function hash($value)
    {
        return crypt($value, self::CRYPT_SALT) . md5($value . self::CRYPT_SALT);
    }


}