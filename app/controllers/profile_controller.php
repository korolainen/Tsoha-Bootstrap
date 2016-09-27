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
		if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['phone'])){
			$hash = '';
			if(!empty($_POST['password'])){
				$hash = Security::hash_with_salt($_POST['password']);
			}
			$me = new Me(array('first_name' => $_POST['first_name'], 
								'last_name' => $_POST['last_name'],
								'phone' => $_POST['phone'],
								'hash' => $hash,
								'id' => LoggedUser::id()));
			$me->update();
		}
		self::return_back('/search');
	}
	
	public static function login(){
		if(!isset($_POST['account']) || !isset($_POST['password'])) Redirect::to('');
		$remember_me = false;
		if(isset($_POST['remember'])) $remember_me = true; 
		if(LoggedUser::login($_POST['account'], $_POST['password'], $remember_me)){
			Redirect::to('/search');
		}
		Redirect::to('');
	}
	
}