<?php

class Redirect{
    public static function doIt($url){
        header( "location:" . $url);
        exit();
    }
}