<?php

namespace core;

class User
{
    /**
     * @var
     */
    private $_login;

    /**
     * @var
     */
    private $_pwd;

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        session_start();
        $this->_login = isset($_SESSION['login']);
        session_write_close();
        return !empty($this->_login);
    }

    /**
     * @param $login
     * @param $pwd
     * @return bool
     */
    public function login($login, $pwd)
    {
        $this->_login = $login;
        $this->_pwd = $pwd;
        if (!empty(array_filter(CmsLite::$app->config['access'], [$this, 'findUser']))){
            session_start();
            $_SESSION['login'] = $login;
            session_write_close();
            return true;
        }
        return false;
    }

    /**
     * @param $row
     * @return bool
     */
    public function findUser($row)
    {
        return $row['login'] == $this->_login && $row['password'] == md5($this->_pwd);
    }
}