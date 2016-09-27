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
	
}