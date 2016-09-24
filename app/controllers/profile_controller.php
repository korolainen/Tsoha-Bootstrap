<?php
class ProfileController extends BaseController{

	public static function profile(){
		$me = Me::get_logged_user();
		View::make('login/profile.html', array('me' => $me));
	}
	
}