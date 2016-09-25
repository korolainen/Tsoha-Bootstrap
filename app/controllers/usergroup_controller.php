<?php
class UsergroupController extends BaseController{

	public static function groups(){
		self::check_logged_in();
		$groups = Usergroup::all();
		View::make('groups/groups.html', array('groups' => $groups));
	}
	
	public static function group($id){
		$group = Usergroup::get($id);
		View::make('groups/group.html', array('usergroup' => $group));
	}
	
	public static function create_new(){
		
	}
	
	public static function edit($id){
		
	}
	
	public static function remove($id){
		
	}

}