<?php
class LoggedUser{
    private static final $SESSION_KEY = 'shopuserkey';
    private static $userid = '';
    private static $data = array();
    
	public static function init_login(){
		self::$userid = Session::get(self::$SESSION_KEY);
		if(empty(self::$userid)){
			self::$userid = Cookies::get(self::$SESSION_KEY);
		}
		$row = self::username(self::$userid);
		self::set_user_data($row);
	}
	
	public static function id(){
		return intval(self::$userid);
	}
	
    public static function set_user_data($row){
    	if(array_key_exists('account', $row)){
			Session::set(self::$SESSION_KEY, $row['id']);
			Cookies::set(self::$SESSION_KEY, $row['id']);
			self::$data = $row;
    	}
    }
	
    public static function login($username, $password, $remember_me = false){
        self::destroy_session_and_cookies();
        $row = User::get_by_account_and_pass($username, $password);
        if(isset($row['id'])){
			Session::set(self::$SESSION_KEY, $id);
			if($remember_me){
			    Cookies::set(self::$SESSION_KEY, $id);
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
