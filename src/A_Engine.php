<?php
/**
 * Created by PhpStorm.
 * User: spiker
 * Date: 13.02.15
 * Time: 20:13
 */

require_once realpath(dirname(__FILE__) . '/I_Engine.php');
require_once realpath(dirname(__FILE__) . '/SocException.php');

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

    public function authenticate(){

    }

    public function getAccessToken(){

    }

}