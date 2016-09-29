<?php
class FrontController extends BaseController{

	public static function login_page(){
		if(LoggedUser::is_logged()) Redirect::to('/search');
		View::make('login/login.html', array('errors' => Messages::errors(),
												'attributes' => Session::pop('attributes')));
	}  
  	
  	public static function logout(){
  		LoggedUser::logout();
    	Redirect::to('');
    }
    /*
	public static function forgotpass(){
		View::make('login/forgotpass.html');
	}
	*/
	public static function register(){
		View::make('login/signup.html', array('errors' => Messages::errors(),
												'attributes' => Session::pop('attributes')));
	}
    

}