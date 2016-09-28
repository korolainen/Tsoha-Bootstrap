<?php
class UsergroupController extends BaseController{

	public static function groups(){
		$groups = Usergroup::all();
		View::make('groups/groups.html', array('groups' => $groups));
	}
	
	public static function group($id){
		$group = Usergroup::get($id);
		$usergroup_users = UsergroupUsers::users($id);
		View::make('groups/group.html', array('group' => $group, 
												'visibility' => CssClass::visibility(), 
												'usergroup_users' => $usergroup_users,
												'errors' => Messages::errors()));
	}
	
	public static function find(){
		if(!isset($_GET['q'])) exit();
		$usergroups = Usergroup::find($_GET['q']);
		View::make('groups/find.html', array('groups' => $usergroups));
	}
	
	public static function create_new_form(){
		View::make('groups/new_group.html', array('errors' => Messages::errors()));
	}
	
	public static function create_new(){
		CheckPost::required_redirect(array('name'), '/groups');
		$usergroup = new Usergroup(array('name' => $_POST['name']));
		$usergroup->check_errors_and_redirect('/groups/new');
		$usergroup_id = $usergroup->save();
		if(isset($_POST['account_name'])){
			$account_names = array();
			foreach($_POST['account_name'] as $account_name){
				if(isset($account_names[$account_name])) continue;
				$account_names[$account_name] = $account_name;
				$user = User::check_account($account_name);
				if(empty($user)) continue;
				UsergroupUsers::insert($usergroup_id, $user['id']);
			}
		}
		Redirect::back('/groups/group/'.$usergroup_id);
	}
	
	
	public static function edit($id){
		CheckPost::required_redirect(array('name'), '/groups/group/'.$id);
		$usergroup = new Usergroup(array('name' => $_POST['name'], 'id' => $id));
		$usergroup->check_errors_and_redirect('/groups/group/'.$id.'?edit=true');
		$usergroup->update();
		Redirect::back('/groups/group/'.$id);
	}
	
	public static function remove($id){
		Usergroup::remove($id);
		Redirect::back('/groups');
	}

}