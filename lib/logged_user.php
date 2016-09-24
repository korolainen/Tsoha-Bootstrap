<?php
class LoggedUser{
    private static $SESSION_KEY = 'shopuserkey';
    private static $user_secure_key = '';
    private static $user_id = 1;//TODO muutetaan
    private static $data = array();
    
	public static function init_login(){
		self::$user_secure_key = Session::get(self::$SESSION_KEY);
		if(empty(self::$user_secure_key)){
			self::$user_secure_key = Cookies::get(self::$SESSION_KEY);
		}
		$row = User::get_by_secure_key(self::$user_secure_key);
		self::set_user_data($row);
	}
	
	public static function id(){
		return self::$user_id;
	}
	
    public static function set_user_data($row){
    	if(array_key_exists('account', $row)){
			Session::set(self::$SESSION_KEY, $row['id']);
			Cookies::set(self::$SESSION_KEY, $row['id']);
			self::$data = $row;
			self::$user_id = intval($row['id']);
    	}
    }
	
    public static function login($username, $password, $remember_me = false){
        self::destroy_session_and_cookies();
        $row = User::get_by_account_and_pass($username, $password);
        if(isset($row['id'])){
        	$secure_key = md5($row['id'].substr($row['hash'], 33));
			Session::set(self::$SESSION_KEY, $secure_key);
			if($remember_me){
			    Cookies::set(self::$SESSION_KEY, $secure_key);
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
        self::destroy_session();
        self::destroy_cookies();
	}
	public static function logout(){
	    self::destroy_session_and_cookies();
	}
}
