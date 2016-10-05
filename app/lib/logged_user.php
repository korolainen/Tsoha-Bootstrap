<?php

class LoggedUser{
    const SESSION_USER_KEY = 'shopuserkey';
    const SESSION_USER_ID = 'shopuserid';
    private static $user_secure_key = '';
    private static $user_secure_id = '';
    private static $user_id = 0;
    private static $is_logged = false;
    
	public static function init_login(){
		self::$user_secure_key = Session::get(self::SESSION_USER_KEY);
		self::$user_secure_id = Session::get(self::SESSION_USER_ID);
		if(empty(self::$user_secure_key)){
			self::$user_secure_key = Cookies::get(self::SESSION_USER_KEY);
			self::$user_secure_id = Cookies::get(self::SESSION_USER_ID);
		}
		self::set_user_data(Me::get_by_secure_key(self::$user_secure_id, self::$user_secure_key));
	}
	
	public static function id(){
		return self::$user_id;
	}
	
	public static function is_logged(){
		return self::$is_logged;
	}
	

	private static function set_user_session($secure_key, $id){
		Session::set(self::SESSION_USER_KEY, $secure_key);
		Session::set(self::SESSION_USER_ID, $id);
	}
	

	private static function set_user_cookie($secure_key, $id){
		Cookies::set(self::SESSION_USER_KEY, $secure_key);
		Cookies::set(self::SESSION_USER_ID, $id);
	}
	
    public static function set_user_data($user){
    	if(!empty($user)){
    		$secure_key = Security::build_session_key($user->hash, $user->id);
			self::set_user_session($secure_key, $user->id);
			self::$user_id = intval($user->id);
			self::$is_logged = true;
    	}
    }
    
    public static function login($username, $password, $remember_me = false){
        $user = User::get_by_account_and_pass($username, $password);
        if(empty($user)) return false;
        $secure_key = Security::build_session_key($user->hash, $user->id);
        self::set_user_session($secure_key, $user->id);
        if($remember_me){
        	self::set_user_cookie($secure_key, $user->id);
        }
        return true;
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
				if(substr($cookie, 0, 9)=='PHPSESSID') continue;
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
