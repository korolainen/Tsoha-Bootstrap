<?php
class ProductController extends BaseController{

	public static function products(){
		$products = Product::all();
		View::make('products/products.html', array('products' => $products, 'user'=>Me::get()));
	}
	
	public static function product($id){
		$product = Product::get($id);
		View::make('products/product.html', array('product' => $product, 'visibility' => CssClass::visibility()));
	}
	
	public static function create_new_form(){
		View::make('products/new_product.html');
	}
	
	public static function create_new(){
		
	}
	
	public static function edit($id){
		if(array_key_exists('name', $_POST)){
			Shop::update($_POST['name'], $id);
		}
		self::return_back('/products/product/'.$id);
	}
	
	public static function remove($id){
		Product::remove($id);
		self::return_back('/products');
	}
}