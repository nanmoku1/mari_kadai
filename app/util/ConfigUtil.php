<?php
namespace app\util;

class ConfigUtil{
    public static $_config = [];

    public static function init(){
        self::$_config = require_once(dirname(dirname(dirname(__FILE__)))."/config_.php");
    }

	public static function read($confKey){
        return array_key_exists($confKey, self::$_config) ? self::$_config[$confKey]:null;
    }
}