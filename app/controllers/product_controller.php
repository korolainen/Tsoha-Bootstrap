<?php
class ProductController extends BaseController{


	public static function products(){
		View::make('products/products.html');
	}
	
	public static function product(){
		View::make('products/product.html');
	}
}