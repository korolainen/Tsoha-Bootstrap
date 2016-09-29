<?php
class ShoppinglistProductController extends BaseController{
	
	public static function add($shoppinglist_id){
		CheckPost::required_redirect(array('name'), '/shoppinglists/shoppinglist/'.$shoppinglist_id);
		$product_id = CheckData::post_by_key('product_id');
		$product = Product::get($product_id);
		if(empty($product)){
			$product = new Product(array('name' => $_POST['name']));
			$product->check_errors_and_redirect();
			$product_id = $product->save();
		}
		$shopproduct = new ShoppinglistProduct(array('product_id' => $product_id, 'shoppinglist_id' => $shoppinglist_id));
		$shopproduct->check_errors_and_redirect('/shoppinglists/shoppinglist/'.$shoppinglist_id.'?add=true');
		$shopproduct->save();
		Redirect::back('/shoppinglists/shoppinglist/'.$shoppinglist_id);
	}
	
	public static function remove($shoppinglist_id, $product_id){
		CheckPermission::shoppinglist($shoppinglist_id);
		ShoppinglistProduct::remove($shoppinglist_id, $product_id);
		Redirect::back('/shoppinglists/shoppinglist/'.$shoppinglist_id);
	}
}