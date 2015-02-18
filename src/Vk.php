<?php

/**
 * Created by PhpStorm.
 * User: spiker
 * Date: 13.02.15
 * Time: 20:13
 */

require_once __DIR__. '/A_Engine.php';

class Vk extends A_Engine {

    const API_VERSION = '5.28';

    public $back_url;
    public $scope = 'friends,video,offline';
    public $urls = array(
        'auth_url' => 'https://oauth.vk.com/authorize?client_id=%s&scope=%s&redirect_uri=%s&response_type=code&v=%s&state="SESSION_STATE" ',
        'access_url' => 'https://oauth.vk.com/access_token?client_id=%s&client_secret=%s&code=%s&redirect_uri=%s',
        'link' => 'https://api.vk.com/method/',
    );

    public function authenticate() {

        if ($this->isAuth()) return;

        if (isset($_GET['code'])) {
            $this->getAccessToken();
        } else {
            $_SESSION['back_url'] = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
            $this->getRequestToken();
        }
    }

    protected function getRequestToken(){

        $url = $this->getAuthUrl();

        header('Location:'.$url);
        exit;
    }

    protected function getAccessToken(){

        if(!isset($_GET['code'])) throw new SocException('Not find "code"');

        $url = $this->getAccessUrl($_GET['code']);

        $curl = new Curl\Curl();
        $resp = $curl->get($url);

        if(isset($resp->error)) throw new SocException($resp->error_description);

        $this->setToken($resp->access_token);
        $_SESSION['timeout']    = time() + $resp->expires_in;
        $_SESSION['user_id']    = $resp->user_id;
        $_SESSION['vk_auth']    = true;

        header('Location:'.$_SESSION['back_url']);
        exit;
    }

    /**
     * Check session time
     * @return bool
     */
    protected static function checkTime(){

        if(isset($_SESSION['timeout']) && time() < $_SESSION['timeout'])
            return true;
        else
            return true;
    }

    /**
     * get Url for auth
     * @return string
     */
    protected function getAuthUrl(){

        return sprintf($this->urls['auth_url'], $this->config['app_id'], $this->scope, $_SESSION['back_url'], self::API_VERSION);
    }

    /**
     * get Url for access token
     * @return string
     */
    protected function getAccessUrl($code){

        return sprintf($this->urls['access_url'], $this->config['app_id'], $this->config['secret_key'], $_GET['code'], $_SESSION['back_url']);
    }

    public function run($method,$data = array(), $auth = false){

        if($auth) {
            $this->authenticate();
            $data['access_token'] = $this->getToken();
        }

        $data = http_build_query($data);

        $url = $this->urls['link'].'photos.search?'.$data;

        $curl = new Curl\Curl();
        $resp = $curl->get($url);

        return json_decode($resp);
    }

}