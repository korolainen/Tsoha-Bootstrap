<?php
class ProductController extends BaseController{

	public static function products(){
		self::check_logged_in();
		$products = Product::all();
		View::make('products/products.html', array('products' => $products, 'user'=>Me::get()));
	}
	
	public static function product(){
		self::check_logged_in();
		View::make('products/product.html');
	}
}