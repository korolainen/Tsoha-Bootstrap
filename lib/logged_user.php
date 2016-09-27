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
    private static $is_logged = false;
    
	public static function init_login(){
		Session::set(self::SESSION_KEY, '9c0db2fbc31e06d481f02eedc0aa1f19'); //TODO comment out
		
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
    	if(array_key_exists('account', $row)){
    		$secure_key = self::build_secure_key($row['hash'], $row['id']);
			Session::set(self::SESSION_KEY, $secure_key);
			Cookies::set(self::SESSION_KEY, $secure_key);
			self::$data = $row;
			self::$user_id = intval($row['id']);
			self::$is_logged = true;
    	}
    }
	
    private static function build_secure_key($hash, $id){
    	$salt = substr($hash, 33);
    	return md5($id.$salt);
    }
    
    public static function login($username, $password, $remember_me = false){
        self::destroy_session_and_cookies();
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

    private static function destroy_session(){
    	foreach($_SESSION as $k => $v){
    		unset($_SESSION[k]);
    	}
    }
	private static function destroy_cookies(){
		foreach($_COOKIE as $k => $v){
			//if($k=='PHPSESSID') continue;
			Cookies::remove($k);
		}
	}
	private static function destroy_session_and_cookies(){
		self::$is_logged = false;
        self::destroy_session();
        self::destroy_cookies();
	}
	public static function logout(){
	    self::destroy_session_and_cookies();
	}
}
LoggedUser::init_login();
