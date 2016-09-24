<?php
class GroupController extends BaseController{

	public static function groups(){
		View::make('groups/groups.html');
	}
	
	public static function group(){
		View::make('groups/group.html');
	}

}