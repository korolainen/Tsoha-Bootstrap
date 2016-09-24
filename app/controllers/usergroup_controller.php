<?php
class UsergroupController extends BaseController{

	public static function groups(){
		$groups = Usergroup::all();
		View::make('groups/groups.html', array('groups' => $groups));
	}
	
	public static function group(){
		View::make('groups/group.html');
	}

}