<?php

/**
 * Created by PhpStorm.
 * User: spiker
 * Date: 13.02.15
 * Time: 20:13
 */

require_once __DIR__. '/A_Engine.php';

class Google extends A_Engine {

    const API_VERSION = '1.0';

    public $urls = array(
        'link' => 'https://ajax.googleapis.com/ajax/services/search/',
    );

    public function run($method, $data = array(), $auth = false, $type = 'get'){

        $method = trim($method, '/');

        $data['v'] = self::API_VERSION;
        $data['userip'] = $_SERVER['REMOTE_ADDR'];

        $url = $this->urls['link'].$method;

        $curl = new Curl\Curl();
        $resp = $curl->$type($url, $data);

        return $resp;
    }

}