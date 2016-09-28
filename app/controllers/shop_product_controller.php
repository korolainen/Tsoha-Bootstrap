<?php
class ShopProductController extends BaseController{


	public static function edit($shop_id, $product_id){
		if(isset($_POST['price'])){
			$data = array('price' => CheckData::text_to_float($_POST['price']),
							'shop_id' => $shop_id,
							'product_id' => $product_id
			);
			$shop_product = new ShopProduct($data);
			$shop_product->update();
			$sp = ShopProduct::get($shop_id, $product_id);
			if($sp->is_cheapest=='1') echo 'fa fa-check-square-o';
			else echo 'fa fa-check-square';
		}
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