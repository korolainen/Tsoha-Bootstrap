<?php
class ProfileController extends BaseController{
	
	public static function profile(){
		View::make('profile/profile.html');
	}
	
	public static function check_account(){
		if(!isset($_GET['account'])) exit();
		$account = User::check_account($_GET['account']);
		if(!empty($account)) echo 'ok';
		exit();
	}
	
	public static function edit(){
		CheckPost::required_redirect(array('first_name','last_name','phone'), '/search');
		$hash = '';
		if(!empty($_POST['password'])) $hash = Security::hash_with_salt($_POST['password']);
		$me = new Me(array('first_name' => $_POST['first_name'], 
							'last_name' => $_POST['last_name'],
							'phone' => $_POST['phone'],
							'hash' => $hash,
							'id' => LoggedUser::id()));
		$me->check_errors_and_redirect();
		$me->update();
		Redirect::back('/search');
	}
	
	public static function login(){
		CheckPost::required_redirect(array('account','password'), '');
		$remember_me = false;
		if(isset($_POST['remember'])) $remember_me = true; 
		if(LoggedUser::login($_POST['account'], $_POST['password'], $remember_me)){
			Redirect::to('/search');
		}
		Redirect::to('');
	}
	
}