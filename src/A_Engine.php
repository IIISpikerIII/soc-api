<?php
/**
 * Created by PhpStorm.
 * User: spiker
 * Date: 13.02.15
 * Time: 20:13
 */

require_once __DIR__ . '/I_Engine.php';

abstract class A_Engine implements I_Engine {

    public $curl;
    public $config = array(
        'redirect_url'  => 'url',
        'app_id'        => 'id',
        'secret_key'    => 'key'
    );

    /**
     * Constractor Engine, add config params
     * @param array $conf
     */
    public function __construct($conf = array()){

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        foreach($this->config as $key => &$val)
            if(isset($conf[$key])) $val = $conf[$key];
    }

    protected function isAuth() {
        return isset($_SESSION[__CLASS__.'auth']) && $_SESSION[__CLASS__.'auth'];
    }

    protected function setToken($token) {

        $_SESSION[__CLASS__.'token'] = $token;
        $_SESSION[__CLASS__.'auth'] = ($token !== null);
    }

    protected function getToken() {

        return isset($_SESSION[__CLASS__.'token'])? $_SESSION[__CLASS__.'token']: false;
    }

}