<?php
class ShoppinglistController extends BaseController{

	public static function shoppinglists(){
		self::check_logged_in();
		$shoppinglists = Shoppinglist::all();
		View::make('shoppinglists/shoppinglists.html', array('shoppinglists' => $shoppinglists));
	}
	
	public static function shoppinglist(){
		self::check_logged_in();
		View::make('shoppinglists/shoppinglist.html');
	}
	
}