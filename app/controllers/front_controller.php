<?php
class FrontController extends BaseController{

	public static function login(){
		View::make('login/login.html');
	}  
  	
  	public static function logout(){
    	View::make('login/logout.html');
    }
    
	public static function forgotpass(){
		View::make('login/forgotpass.html');
	}
	
	public static function register(){
		View::make('login/signup.html');
	}
    

}