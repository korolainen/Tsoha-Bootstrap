<?php
class UsergroupController extends BaseController{

	public static function groups(){
		self::check_logged_in();
		$groups = Usergroup::all();
		View::make('groups/groups.html', array('groups' => $groups));
	}
	
	public static function group(){
		self::check_logged_in();
		View::make('groups/group.html');
	}

}