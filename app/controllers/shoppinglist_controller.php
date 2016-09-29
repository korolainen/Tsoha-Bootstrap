<?php
class ShoppinglistController extends BaseController{

	public static function shoppinglists(){
		$shoppinglists = Shoppinglist::all();
		View::make('shoppinglists/shoppinglists.html', array('shoppinglists' => $shoppinglists));
	}
	public static function find(){
		if(!isset($_GET['q'])) exit();
		$shoppinglists = Shoppinglist::find($_GET['q']);
		View::make('shoppinglists/find.html', array('shoppinglists' => $shoppinglists));
	}
	public static function link($shoppinglist_id){
		if(!isset($_GET['q'])) exit();
		$products = Product::find_not_in_shoppinglist($_GET['q'], $shoppinglist_id);
		View::make('products/linkproduct.html', array('products' => $products));
	}
	public static function shoppinglist($id){
		$shoppinglist = Shoppinglist::get($id);
		$shopppinglist_products = ShoppinglistProduct::products_in_shoppinglist($id);
		View::make('shoppinglists/shoppinglist.html', array('shoppinglist' => $shoppinglist, 
															'visibility' => CssClass::visibility(), 
															'shoppinglist_products' => $shopppinglist_products,
															'errors' => Messages::errors(),
															'attributes' => Session::pop('attributes')));
	}
	
	public static function create_new_form(){
		$usergroups = Usergroup::all();
		View::make('shoppinglists/new_shoppinglist.html', array('usergroups' => $usergroups, 
																'errors' => Messages::errors(),
																'attributes' => Session::pop('attributes')));
	}
	
	public static function create_new(){
		CheckPost::required_redirect(array('name','active'), '/shoppinglists');
		$shoppinglist = new Shoppinglist(array('name' => $_POST['name'], 'active' => $_POST['active']));
		$shoppinglist->check_errors_and_redirect('/shoppinglists/new');
		$shoppinglist_id = $shoppinglist->save();
		if(isset($_POST['group'])){
			$usergroups = array();
			foreach($_POST['group'] as $usergroup_id){
				if(isset($usergroups[$usergroup_id])) continue;
				$usergroups[$usergroup_id] = $usergroup_id;
				$usergroup = Usergroup::get($usergroup_id);
				if(empty($usergroup)) continue;
				ShoppinglistUsergroup::insert($shoppinglist_id, $usergroup->id);
			}
		}
		Redirect::back('/shoppinglists/shoppinglist/'.$shoppinglist->id);
	}
	
	public static function edit($id){
		CheckPost::required_redirect(array('name','active'), '/shoppinglists/shoppinglist/'.$id);
		$shoppinglist = new Shoppinglist(array('name' => $_POST['name'], 'active' => $_POST['active'], 'id' => $id));
		$shoppinglist->check_errors_and_redirect('/shoppinglists/shoppinglist/'.$id.'?edit=true');
		$shoppinglist->update();
		Redirect::back('/shoppinglists/shoppinglist/'.$id);
	}
	
	public static function remove($id){
		Shoppinglist::remove($id);
		Redirect::back('/shoppinglists');
	}
	
}