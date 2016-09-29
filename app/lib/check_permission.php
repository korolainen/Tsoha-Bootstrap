<?php
class CheckPermission{
	private static function redirect($object, $target){
		if(empty($object)) Redirect::to($target);
	}
	public static function shoppinglist($id){
		self::redirect(Shoppinglist::get($id), '/shoppinglists');
	}
	public static function shop($id){
		self::redirect(Shop::get($id), '/shop');
	}
	public static function group($id){
		self::redirect(Usergroup::get($id), '/groups');
	}
	public static function product($id){
		self::redirect(Product::get($id), '/products');
	}
	

	public static function product_object($object){
		self::redirect($object, '/groups');
	}
	public static function group_object($object){
		self::redirect($object, '/groups');
	}
	public static function shop_object($object){
		self::redirect($object, '/groups');
	}
	public static function shoppinglist_object($object){
		self::redirect($object, '/groups');
	}
	
}