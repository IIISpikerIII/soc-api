<?php

/**
 * Created by PhpStorm.
 * User: spiker
 * Date: 13.02.15
 * Time: 20:13
 */

interface I_Engine {

    public function authenticate();
    public function getAccessToken();

}