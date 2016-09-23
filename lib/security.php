<?php
class Security{
	private static $secret_key = '4235a4s65xr6itqoinhdyytun489msk8h9a9d92hk78l8itf67o8v99l7r6';
	public static function hash_with_salt($passwd){
		//$salt = password_hash($passwd, PASSWORD_BCRYPT, array('cost' => 12));
		$salt = str_shuffle(self::$secret_key.md5(uniqid()));
		return md5($passwd.$salt).$salt;
	}
	public static function encode($data){
		return base64_encode($data);
	}
	public static function decode($data){
		return base64_decode($data);
	}
	public static function key($key){
		return md5($key.self::secret_key);
	}
}
