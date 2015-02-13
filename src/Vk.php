<?php
/**
 * Created by PhpStorm.
 * User: spiker
 * Date: 13.02.15
 * Time: 20:13
 */

class Vk extends A_Engine {

    const API_VERSION = '5.28';

    public $scope;
    public $urls = array(
        'auth_url' => 'https://oauth.vk.com/authorize?client_id=%s&scope=%s&redirect_uri=%s&response_type=code&v=%s&state="SESSION_STATE" ',
    );

    public function authenticate() {

        if (isset($_GET['code'])) {
            $this->getAccessToken();
        } else {
            $this->getRequestToken();
        }
    }

    public function getRequestToken(){

        $url = $this->getAuthUrl();

        $curl = new Curl\Curl();
    }

    public function getAccessToken(){

    }


    /**
     * get Url for auth
     * @return string
     */
    public function getAuthUrl(){

        return sprintf($this->urls['auth_url'], $this->config['client_id'], $this->scope, $this->config['redirect_uri'], self::API_VERSION);
    }

}