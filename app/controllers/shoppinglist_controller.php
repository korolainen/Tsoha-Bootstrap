<?php
class ShoppinglistController extends BaseController{

	public static function shoppinglists(){
		self::check_logged_in();
		$shoppinglists = Shoppinglist::all();
		View::make('shoppinglists/shoppinglists.html', array('shoppinglists' => $shoppinglists));
	}
	
	public static function shoppinglist(){
		View::make('shoppinglists/shoppinglist.html');
	}
	
	public static function create_new(){
		
	}
	
	public static function edit(){
		
	}
	
	public static function remove(){
		
	}
	
}