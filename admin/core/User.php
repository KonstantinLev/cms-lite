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
        if (!empty(array_filter(CmsLite::$app->json['access'], [$this, 'findUser']))){
            session_start();
            $_SESSION['login'] = $login;
            session_write_close();
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        if($this->isLoggedIn()){
            session_start();
            $login =$_SESSION['login'];
            session_write_close();
            return $login;
        }
        return '';
    }

    /**
     *
     */
    public function logout()
    {
        session_start();
        if(isset($_SESSION['login'])) unset($_SESSION['login']);
        session_write_close();
    }

    /**
     * @param $row
     * @return bool
     */
    public function findUser($row)
    {
        return $row['login'] == $this->_login && $row['pwd'] == md5($this->_pwd);
    }
}