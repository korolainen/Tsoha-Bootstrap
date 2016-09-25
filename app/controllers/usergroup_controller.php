<?php
class UsergroupController extends BaseController{

	public static function groups(){
		self::check_logged_in();
		$groups = Usergroup::all();
		View::make('groups/groups.html', array('groups' => $groups));
	}
	
	public static function group(){
		View::make('groups/group.html');
	}
	
	public static function create_new(){
		
	}
	
	public static function edit(){
		
	}
	
	public static function remove(){
		
	}

}