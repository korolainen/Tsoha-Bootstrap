<?php
class ShoppinglistController extends BaseController{

	public static function shoppinglists(){
		self::check_logged_in();
		$shoppinglists = Shoppinglist::all();
		View::make('shoppinglists/shoppinglists.html', array('shoppinglists' => $shoppinglists));
	}
	
	public static function shoppinglist($id){
		$shoppinglist = Shoppinglist::get($id);
		View::make('shoppinglists/shoppinglist.html', array('shoppinglist' => $shoppinglist));
	}
	
	public static function create_new(){
		
	}
	
	public static function edit($id){
		
	}
	
	public static function remove($id){
		
	}
	
}