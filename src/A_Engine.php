<?php
/**
 * Created by PhpStorm.
 * User: spiker
 * Date: 13.02.15
 * Time: 20:13
 */

abstract class A_Engine implements I_Engine {

    public $curl;
    public $config = array(
        'redirect_url'  => '/',
        'app_id'        => '000'
    );

    /**
     * Constractor Engine, add config params
     * @param array $conf
     */
    public function __construct($conf = array()){

        foreach($this->config as $key => &$val)
            if(isset($conf[$key])) $val = $conf[$key];
    }

    public function authenticate(){

    }

    public function getAccessToken(){

    }

}