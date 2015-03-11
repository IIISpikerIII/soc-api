<?php

/**
 * Created by PhpStorm.
 * User: spiker
 * Date: 13.02.15
 * Time: 20:13
 */

require_once __DIR__. '/A_Engine.php';

class Instagram extends A_Engine {

    const API_VERSION = '5.28';

    public $back_url;
    public $scope = 'basic';//basic+comments+relationships+likes
    public $urls = array(
        'auth_url' => 'https://api.instagram.com/oauth/authorize/?client_id=%s&redirect_uri=%s&response_type=code',
        'access_url' => 'https://api.instagram.com/oauth/access_token?client_id=%s&client_secret=%s&grant_type=authorization_code&redirect_uri=%s&code=%s',
        'link' => 'https://api.instagram.com/v1/',
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

        $query = parse_url($url);
        parse_str($query['query'], $params);
        $query_url = $query['scheme'].'://'.$query['host'].$query['path'];

        $curl = new Curl\Curl();
        $resp = $curl->post($query_url, $params);

        if(isset($resp->error_message)) throw new SocException($resp->error_message, $resp->code);

        $this->setToken($resp->access_token);


        header('Location:'.$_SESSION['back_url']);
        exit;
    }

    /**
     * get Url for auth
     * @return string
     */
    protected function getAuthUrl(){

        return sprintf($this->urls['auth_url'], $this->config['app_id'], $_SESSION['back_url']);
    }

    /**
     * get Url for access token
     * @return string
     */
    protected function getAccessUrl($code){

        return sprintf($this->urls['access_url'], $this->config['app_id'], $this->config['secret_key'], $_SESSION['back_url'],$code);
    }

    public function run($method, $data = array(), $auth = false, $type = 'get'){

        $method = trim($method, '/');

        if($auth) {
            $this->authenticate();
            $data['access_token'] = $this->getToken();
        }

        $url = $this->urls['link'].$method;

        $curl = new Curl\Curl();
        $resp = $curl->$type($url, $data);

        return $resp;
    }

}