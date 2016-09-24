<?php
class ProfileController extends BaseController{
	
	public static function profile(){
		self::check_logged_in();
		$me = Me::get_logged_user();
		View::make('profile/profile.html', array('me' => $me));
	}
	
}