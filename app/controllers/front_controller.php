<?php
class FrontController extends BaseController{

	public static function login_page(){
		if(LoggedUser::is_logged()) Redirect::to('/search');
		View::make('login/login.html');
	}  
  	
  	public static function logout(){
  		LoggedUser::logout();
    	Redirect::to('');
    }
    
	public static function forgotpass(){
		View::make('login/forgotpass.html');
	}
	
	public static function register(){
		View::make('login/signup.html');
	}
    

}