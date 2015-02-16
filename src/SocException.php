<?php

class SocException extends Exception
{
    public function __construct($message = "", $code = 0) {
        parent::__construct();

        print_r($message);
        exit();
    }
}
