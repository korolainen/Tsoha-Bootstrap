<?php

class Cookies{
	public static function set($key, $value, $time = 0){
		if($time==0){
			$time = 60 * 60 * 10 + time();
		}
		setcookie(Security::key($key), Security::encode($value), $time, '/');
	}
	public static function get($key){
		if(isset($_COOKIE[Security::key($key)])){
			return Security::decode($_COOKIE[Security::key($key)]);
		}
		return '';
	}
	public static function is_set($key){
		return (isset($_COOKIE[Security::key($key)]));
	}
	public static function remove($key){
		self::set(Security::key($key), "", time()-3600);
		setcookie(Security::key($key), "", time()-3600);
		setcookie(Security::key($key), "", time()-3600, '');
		setcookie(Security::key($key), "", time()-3600, '/');
	}
}