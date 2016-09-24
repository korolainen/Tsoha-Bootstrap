<?php
class ShoppinglistController extends BaseController{

	public static function shoppinglists(){
		$shoppinglists = Shoppinglist::all();
		View::make('shoppinglists/shoppinglists.html', array('shoppinglists' => $shoppinglists));
	}
	
	public static function shoppinglist(){
		View::make('shoppinglists/shoppinglist.html');
	}
	
}