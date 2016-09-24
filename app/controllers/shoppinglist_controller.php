<?php
class ShoppinglistController extends BaseController{

	public static function shoppinglists(){
		View::make('shoppinglists/shoppinglists.html');
	}
	
	public static function shoppinglist(){
		View::make('shoppinglists/shoppinglist.html');
	}
	
}