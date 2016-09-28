<?php
class UsergroupUserController extends BaseController{

	
	public static function add($usergroup_id){
		if(isset($_POST['account_name'])){
			$account_names = array();
			$usergroup_users = UsergroupUsers::users($usergroup_id);
			foreach($_POST['account_name'] as $account_name){
				if(isset($account_names[$account_name])) continue;
				$account_names[$account_name] = $account_name;
				$user = User::check_account($account_name);
				if(empty($user)) continue;
				if(isset($usergroup_users[$user['id']])) continue;
				if(intval($user['id'])==LoggedUser::id()) continue;
				UsergroupUsers::insert($usergroup_id, $user['id']);
			}
		}
		self::return_back('/groups/group/'.$usergroup_id);
	}
	
	public static function remove($usergroup_id, $users_id){
		UsergroupUsers::remove($usergroup_id, $users_id);
		self::return_back('/groups/group/'.$usergroup_id);
	}
}