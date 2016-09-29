<?php
class UsergroupUserController extends BaseController{

	
	public static function add($usergroup_id){
		CheckPost::required_redirect(array('account_name'), '/groups/group/'.$usergroup_id);
		$account_names = array();
		$usergroup_users = UsergroupUsers::users($usergroup_id);
		$account_names = array_unique($_POST['account_name']);
		foreach($account_names as $account_name){
			if(!User::account_exists($account_name)) continue;
			$user_id = User::check_account_id($account_name);
			if(isset($usergroup_users[$user_id])) continue;
			if(intval($user_id)==LoggedUser::id()) continue;
			UsergroupUsers::insert($usergroup_id, $user_id);
		}
		Redirect::back('/groups/group/'.$usergroup_id);
	}
	
	public static function remove($usergroup_id, $users_id){
		UsergroupUsers::remove($usergroup_id, $users_id);
		Redirect::back('/groups/group/'.$usergroup_id);
	}
}