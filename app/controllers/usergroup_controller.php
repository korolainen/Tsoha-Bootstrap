<?php
class UsergroupController extends BaseController{

	public static function groups(){
		$groups = Usergroup::all();
		View::make('groups/groups.html', array('groups' => $groups));
	}
	
	public static function group($id){
		$group = Usergroup::get($id);
		View::make('groups/group.html', array('group' => $group, 'visibility' => CssClass::visibility()));
	}
	
	public static function create_new_form(){
		View::make('groups/new_group.html');
	}
	
	public static function create_new(){
		if(isset($_POST['name'])){
			$usergroup = new Usergroup(array('name' => $_POST['name']));
			$usergroup_id = $usergroup->save();
			if(array_key_exists('account_name', $_POST)){
				$account_names = array();
				foreach($_POST['account_name'] as $account_name){
					if(isset($account_names[$account_name])) continue;
					$account_names[$account_name] = $account_name;
					$user = User::check_account($account_name);
					if(empty($user)) continue;
					UsergroupUsers::insert($usergroup_id, $user['id']);
				}
			}	
			self::return_back('/groups/group/'.$usergroup_id);
		}
		self::return_back('/groups');
	}
	
	
	public static function edit($id){
		if(isset($_POST['name'])){
			Usergroup::update($_POST['name'], $id);
		}
		self::return_back('/groups/group/'.$id);
	}
	
	public static function remove($id){
		Usergroup::remove($id);
		self::return_back('/groups');
	}

}