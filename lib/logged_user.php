<?php
/*
 * Käyttäjien avaimet
 * 1: 9c0db2fbc31e06d481f02eedc0aa1f19
 * 2: 987213db43a74773834c0b5a0d0f4e47
 * 3: a79138800ac3fc26ad703aca3d3a6230
 * */
class LoggedUser{
    const SESSION_KEY = 'shopuserkey';
    private static $user_secure_key = '';
    private static $user_id = 0;
    private static $data = array();
    private static $hash = '';
    private static $is_logged = false;
    
	public static function init_login(){
		//Session::set(self::SESSION_KEY, '9c0db2fbc31e06d481f02eedc0aa1f19'); //TODO comment out
		self::$user_secure_key = Session::get(self::SESSION_KEY);
		if(empty(self::$user_secure_key)){
			self::$user_secure_key = Cookies::get(self::SESSION_KEY);
		}
		$row = Me::get_by_secure_key(self::$user_secure_key);
		self::set_user_data($row);
	}
	
	public static function id(){
		return self::$user_id;
	}
	
	public static function is_logged(){
		return self::$is_logged;
	}
	
    public static function set_user_data($row){
    	if(isset($row['account'])){
    		$secure_key = self::build_secure_key($row['hash'], $row['id']);
			Session::set(self::SESSION_KEY, $secure_key);
			self::$data = $row;
			self::$hash = $row['hash'];
			self::$user_id = intval($row['id']);
			self::$is_logged = true;
    	}
    }
	
    private static function build_secure_key($hash, $id){
    	$salt = substr($hash, 32);
    	return md5($id.$salt);
    }
    
    public static function login($username, $password, $remember_me = false){
        $row = User::get_by_account_and_pass($username, $password);
        if(isset($row['id'])){
        	$secure_key = self::build_secure_key($row['hash'], $row['id']);
			Session::set(self::SESSION_KEY, $secure_key);
			if($remember_me){
			    Cookies::set(self::SESSION_KEY, $secure_key);
			}
            return true;
        }else{
            self::remove();
        }
        return false;
    }

    private static function remove_sessions(){
    	foreach($_SESSION as $k => $v){
    		unset($_SESSION[$k]);
    	}
    }
	private static function remove_cookies(){
		foreach($_COOKIE as $k => $v){
			if($k=='PHPSESSID') continue;
			Cookies::remove($k);
		}
		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach($cookies as $k => $cookie) {
				if(substr($cookie, 0, 9)=='PHPSESSID') continue; //preserving session
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', time()-1000);
				setcookie($name, '', time()-1000, '/');
			}
		}
		ini_set('session.gc_max_lifetime', 0);
		ini_set('session.gc_probability', 1);
		ini_set('session.gc_divisor', 1);
	}
	private static function remove_sessions_and_cookies(){
		self::$is_logged = false;
		session_regenerate_id();
		$tmp = session_id();
		session_destroy();
        self::remove_sessions();
        self::remove_cookies();
		session_id($tmp);
	}
	public static function logout(){
	    self::remove_sessions_and_cookies();
	}
}
LoggedUser::init_login();
