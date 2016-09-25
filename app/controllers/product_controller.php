<?php
class ProductController extends BaseController{

	public static function products(){
		$products = Product::all();
		View::make('products/products.html', array('products' => $products, 'user'=>Me::get()));
	}
	
	public static function product(){
		View::make('products/product.html');
	}
	
	public static function edit(){
		
	}
	
	public static function remove(){
		self::check_logged_in();
		
	}
}