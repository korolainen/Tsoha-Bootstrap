<?php
class ProductShopController extends BaseController{

	
	public static function add($product_id){
		CheckPost::required_redirect(array('shop_id','price'), '/products/product/'.$product_id);
		$shop_id = intval($_POST['shop_id']);
		$price = CheckData::text_to_float($_POST['price']);
		$shopproduct = new ShopProduct(array('product_id' => $product_id, 'shop_id' => $shop_id, 'price' =>$price));
		$shopproduct->check_errors_and_redirect('/products/product/'.$product_id.'?add=true');
		$shopproduct->save();
		Redirect::to('/products/product/'.$product_id);
	}
}