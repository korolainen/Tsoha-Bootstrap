<?php
class ProductController extends BaseController{

	public static function products(){
		$products = Product::all();
		View::make('products/products.html', array('products' => $products, 'user'=>Me::get()));
	}
	
	public static function product($id){
		$product = Product::get($id);
		View::make('products/product.html', array('product' => $product));
	}
	
	public static function edit($id){
		
	}
	
	public static function remove($id){
		
	}
}