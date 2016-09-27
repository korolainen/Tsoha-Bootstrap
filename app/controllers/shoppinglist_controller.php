<?php
class ShoppinglistController extends BaseController{

	public static function shoppinglists(){
		$shoppinglists = Shoppinglist::all();
		View::make('shoppinglists/shoppinglists.html', array('shoppinglists' => $shoppinglists));
	}
	
	public static function shoppinglist($id){
		$shoppinglist = Shoppinglist::get($id);
		View::make('shoppinglists/shoppinglist.html', array('shoppinglist' => $shoppinglist, 
															'visibility' => CssClass::visibility()));
	}
	
	public static function create_new_form(){
		$usergroups = Usergroup::all();
		View::make('shoppinglists/new_shoppinglist.html', array('usergroups' => $usergroups));
	}
	
	public static function create_new(){
		if(array_key_exists('name', $_POST) && array_key_exists('active', $_POST)){
			$shoppinglist = new Shoppinglist(array('name' => $_POST['name'], 'active' => $_POST['active']));//TODO active
			$shoppinglist_id = $shoppinglist->save();
			if(array_key_exists('group', $_POST)){
				$usergroups = array();
				foreach($_POST['group'] as $usergroup_id){
					if(isset($usergroups[$usergroup_id])) continue;
					$usergroups[$usergroup_id] = $usergroup_id;
					$usergroup = Usergroup::get($usergroup_id);
					if(empty($usergroup)) continue;
					ShoppinglistUsergroup::insert($shoppinglist_id, $usergroup->id);
				}
			}
			self::return_back('/shoppinglists/shoppinglist/'.$shoppinglist->id);
		}
		self::return_back('/shoppinglists');
	}
	
	public static function edit($id){
		if(array_key_exists('name', $_POST) && array_key_exists('active', $_POST)){
			Shoppinglist::update($_POST['name'], $_POST['active'], $id);
		}
		self::return_back('/shoppinglists/shoppinglist/'.$id);
	}
	
	public static function remove($id){
		Shoppinglist::remove($id);
		self::return_back('/shoppinglists');
	}
	
}