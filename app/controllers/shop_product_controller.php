<?php
class ShopProductController extends BaseController{


	public static function edit($shop_id, $product_id){
		CheckPost::required_exit(array('price'));
		$shop = Shop::get($shop_id);
		$prouct = Product::get($product_id);
		if(empty($shop)) exit();
		if(empty($prouct)) exit();
		$shop_product = new ShopProduct(array('price' => CheckData::text_to_float($_POST['price']),
				'shop_id' => $shop_id,
				'product_id' => $product_id
		));
		$shop_product->check_price();
		$shop_product->update();
		$sp = ShopProduct::get($shop_id, $product_id);
		if($sp->is_cheapest=='1') echo '1';
		else echo '0';		
		exit();
	}
	
	public static function add($shop_id){
		CheckPost::required_redirect(array('name'), '/shops/shop/'.$shop_id);
		$price = CheckData::text_to_float($_POST['price']);
		$product_id = CheckData::post_by_key('product_id');
		$product = Product::get($product_id);
		if(empty($product)){
			$product = new Product(array('name' => $_POST['name']));
			$product->check_errors_and_redirect();
			$product_id = $product->save();
		}
		$shopproduct = new ShopProduct(array('product_id' => $product_id, 'shop_id' => $shop_id, 'price' =>$price));
		$shopproduct->check_errors_and_redirect('/shops/shop/'.$shop_id.'?add=true');
		$shopproduct->save();
		Redirect::back('/shops/shop/'.$shop_id);
	}
	
	public static function remove($shop_id, $product_id){
		ShopProduct::remove($shop_id, $product_id);
		Redirect::back('/shops/shop/'.$shop_id);
	}
}