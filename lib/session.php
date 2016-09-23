<?php

class Session{
	public static function set($key, $value){
		$_SESSION[Security::key($key)] = $value;
	}

	public static function get($key){
        $name = Security::key($key);
        if(isset($_SESSION[$name])) return $_SESSION[$name];
		return "";
	}

    public static function pop($key){
        $get = self::get($key);
        self::remove($key);
        return $get;
    }

    public static function is_set($key){
        return (isset($_SESSION[Security::key($key)])) ? true : false;
    }

	public static function remove($key){
		$name = Security::key($key);
		if(isset($_SESSION[$name])){
			unset($_SESSION[$name]);
		}
	}
}