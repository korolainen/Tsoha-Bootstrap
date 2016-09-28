<?php
class ProductShopController extends BaseController{

	
	public static function add($product_id){
		$shop_id = intval($_POST['shop_id']);
		$price = CheckData::text_to_float($_POST['price']);
		$shopproduct = new ShopProduct(array('product_id' => $product_id, 'shop_id' => $shop_id, 'price' =>$price));
		$shopproduct->save();
		self::return_back('/products/product/'.$product_id);
	}
}