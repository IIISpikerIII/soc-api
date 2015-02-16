<?php

/**
 * Created by PhpStorm.
 * User: spiker
 * Date: 13.02.15
 * Time: 20:13
 */

require_once realpath(dirname(__FILE__) . '/A_Engine.php');

class Vk extends A_Engine {

    const API_VERSION = '5.28';

    public $scope = 'friends,video,offline';
    public $urls = array(
        'auth_url' => 'https://oauth.vk.com/authorize?client_id=%s&scope=%s&redirect_uri=%s&response_type=code&v=%s&state="SESSION_STATE" ',
        'access_url' => 'https://oauth.vk.com/access_token?client_id=%s&client_secret=%s&code=%s&redirect_uri=%s',
    );

    public function authenticate() {

        if (isset($_SESSION['vk_auth']) && $_SESSION['vk_auth'] && Vk::checkTime()) {
            return;
        }

        if (isset($_GET['code'])) {
            $this->getAccessToken();
        } else {
            $this->getRequestToken();
        }
    }

    public function getRequestToken(){

        $url = $this->getAuthUrl();

        header('Location:'.$url);
        exit;
    }

    public function getAccessToken(){

        if(!isset($_GET['code'])) throw new SocException('Not find "code"');

        $url = $this->getAccessUrl($_GET['code']);

        $curl = new Curl\Curl();
        $resp = $curl->get($url);

        if(isset($resp->error)) throw new SocException($resp->error_description);

        $_SESSION['vk_token']   = $resp->access_token;
        $_SESSION['timeout']    = time() + $resp->expires_in;
        $_SESSION['user_id']    = $resp->user_id;
        $_SESSION['vk_auth']    = true;

        header('Location:'.$this->config['redirect_url']);
        exit;
    }

    /**
     * Check session time
     * @return bool
     */
    public static function checkTime(){

        if(isset($_SESSION['timeout']) && time() < $_SESSION['timeout'])
            return true;
        else
            return true;
    }

    /**
     * get Url for auth
     * @return string
     */
    public function getAuthUrl(){

        return sprintf($this->urls['auth_url'], $this->config['app_id'], $this->scope, $this->config['redirect_url'], self::API_VERSION);
    }

    /**
     * get Url for access token
     * @return string
     */
    public function getAccessUrl($code){

        return sprintf($this->urls['access_url'], $this->config['app_id'], $this->config['secret_key'], $_GET['code'], $this->config['redirect_url']);
    }

}