<?php
class ShopProductController extends BaseController{


	public static function edit($shop_id, $product_id){
		if(array_key_exists('price', $_POST)){
			$data = array('price' => CheckData::text_to_float($_POST['price']));
			ShopProduct::update($data, $product_id, $shop_id);
		}
		echo 'ok';
		exit();
	}
	
	public static function add($shop_id){
		//if(empty($_POST['name'])) error
		$name = $_POST['name'];
		$price = CheckData::text_to_float($_POST['price']);
		$product_id = CheckData::post_by_key('product_id');
		if(empty($product_id)){
			$product = new Product(array('name' => $name));
			$product_id = $product->save();
		}
		$shopproduct = new ShopProduct(array('product_id' => $product_id, 'shop_id' => $shop_id, 'price' =>$price));
		$shopproduct->save();
		self::return_back('/shops/shop/'.$shop_id);
	}
	
	public static function remove($shop_id, $product_id){
		ShopProduct::remove($shop_id, $product_id);
		self::return_back('/shops/shop/'.$shop_id);
	}
}