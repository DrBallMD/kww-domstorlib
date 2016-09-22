<?php

/**
 * Description of Session
 *
 * @author pahhan
 */
class Ds_Helper_Session
{

    private $session_key = '__ds_';

    private function init()
    {
        if (!isset($_SESSION))
        {
            session_start();
        }

        if (!(isset($_SESSION[$this->session_key]) and is_array($_SESSION[$this->session_key])))
            $_SESSION[$this->session_key] = array();
    }

    public function __construct($key = NULL)
    {
        if (!is_null($key))
        {
            $this->session_key = $key;
        }
        $this->init();
    }

    public function set($name, $value)
    {
        $_SESSION[$this->session_key][$name] = $value;
    }

    public function has($name)
    {
        return array_key_exists($name, $_SESSION[$this->session_key]);
    }

    public function get($name, $default = NULL)
    {
        return $this->has($name) ? $_SESSION[$this->session_key][$name] : $default;
    }

    public function getAll()
    {
        return $_SESSION[$this->session_key];
    }

}
