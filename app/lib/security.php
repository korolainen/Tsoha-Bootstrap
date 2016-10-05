<?php
class Security{
	const SECRET_KEY = '4235a4s65xr6itqoinhdyytun489msk8h9a9d92hk78l8itf67o8v99l7r6';
	
	public static function password_verify($password, $hash){
		return (crypt($password, $hash) == $hash);
	}
	
	public static function compare_session_key($hash, $key){
		return self::password_verify($hash, $key);
	}
	
	public static function build_session_key($hash){
		return self::hash_with_salt($hash);
	}
	
	public static function hash_with_salt($password){
		/**
		 * http://php.net/manual/en/function.crypt.php
		 * "Versions of PHP before 5.3.7 only support "$2a$" as the salt prefix"
		 * 
		 * http://php.net/manual/en/faq.passwords.php#faq.password.storing-salts
		 * @return crypt($password, $blowfish_salt);
		 * $algorithm = substr($hashed_password, 0, 4);
		 * $options = substr($hashed_password, 4, 3);
		 * $salt = substr($hashed_password, 7, 21);
		 * $hash = substr($hashed_password, 28);
		 * 
		 * */

		//$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC); //ei toimi users palvelimella
		//$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND); //ei toimi users palvelimella
		//$crypttext = mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $data, MCRYPT_MODE_CBC, $iv); //ei toimi users palvelimella
		//echo bin2hex($iv . $crypttext)."\n";
		
		//$hash = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12))."\n"; //ei toimi users palvelimella
		//echo password_verify ( $password ,  $hash ). "\n";//ei toimi users palvelimella
		
		//$salt = password_hash($passwd, PASSWORD_BCRYPT, array('cost' => 12)); 
		// -> ei toimi users palvelimella vanhan php-version takia
		$blowfish_salt = '$2a$08$'.bin2hex(openssl_random_pseudo_bytes(22));
		return crypt($password, $blowfish_salt);
	}
	public static function encode($data){
		return base64_encode($data);
	}
	public static function decode($data){
		return base64_decode($data);
	}
	public static function key($key){
		return hash('SHA512', $key.self::SECRET_KEY);
	}
}
