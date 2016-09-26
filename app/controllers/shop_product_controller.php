<?php
class ShopProductController extends BaseController{

	
	public static function edit($shop_id, $product_id){
		var_dump($_POST);
		if(array_key_exists('price', $_POST)){
			$data = array('price' => CheckData::text_to_float($_POST['price']),
						'updated' => date('Y-m-d H:i:s'));
			ShopProduct::update($data, $product_id, $shop_id);
		}
		echo 'ok';
		exit();
	}
	
	public static function remove($id){
		
	}
}