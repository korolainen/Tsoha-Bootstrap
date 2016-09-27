<?php
class ShoppinglistProductController extends BaseController{
	
	public static function add($shoppinglist_id){
		//if(empty($_POST['name'])) error
		$name = $_POST['name'];
		$product_id = CheckData::post_by_key('product_id');
		if(empty($product_id)){
			$product = new Product(array('name' => $name));
			$product_id = $product->save();
		}
		$shopproduct = new ShoppinglistProduct(array('product_id' => $product_id, 'shoppinglist_id' => $shoppinglist_id));
		$shopproduct->save();
		self::return_back('/shoppinglists/shoppinglist/'.$shoppinglist_id);
	}
	
	public static function remove($shoppinglist_id, $product_id){
		ShoppinglistProduct::remove($shoppinglist_id, $product_id);
		self::return_back('/shoppinglists/shoppinglist/'.$shoppinglist_id);
	}
}