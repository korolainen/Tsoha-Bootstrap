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
												'errors' => Messages::errors(),
												'attributes' => Session::pop('attributes')));
	}
	
	public static function find(){
		if(!isset($_GET['q'])) exit();
		$usergroups = Usergroup::find($_GET['q']);
		View::make('groups/find.html', array('groups' => $usergroups));
	}
	
	public static function create_new_form(){
		View::make('groups/new_group.html', array('errors' => Messages::errors(),
												'attributes' => Session::pop('attributes')));
	}
	
	public static function create_new(){
		CheckPost::required_redirect(array('name'), '/groups/new');
		$usergroup = new Usergroup(array('name' => $_POST['name']));
		$errors = $usergroup->errors();
		$user_ids = array();
		if(isset($_POST['account_name'])){
			$account_names = array();
			foreach($_POST['account_name'] as $k => $account_name){
				if(empty($account_name)) unset($_POST['account_name'][$k]);
			}
			foreach($_POST['account_name'] as $account_name){
				if(isset($account_names[$account_name])) continue;
				$account_names[$account_name] = $account_name;
				$user = User::check_account($account_name);
				if(empty($user)) {
					$errors[] = 'Käyttäjätunnusta "'.$account_name.'" ei voi liittää ryhmään koska se ei löydy!';
				}
				$user_ids[] = $user['id'];
			}
		}
		Messages::redirect_errors($errors, '/groups/new');
		$usergroup_id = $usergroup->save();
		foreach($user_ids as $user_id){
			UsergroupUsers::insert($usergroup_id, $user_id);
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