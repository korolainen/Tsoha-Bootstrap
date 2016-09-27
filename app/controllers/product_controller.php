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
		if(!empty($_POST['name'])){
			$product = new Product(array('name' => $_POST['name']));
			$product_id = $product->save();
			self::return_back('/products/product/'.$product_id);
		}
		self::return_back('/products');
	}
	
	public static function edit($id){
		if(isset($_POST['name'])){
			$product = new Product(array('name' => $_POST['name'], 'id' => $id));
			$product->update();
		}
		self::return_back('/products/product/'.$id);
	}
	
	public static function remove($id){
		Product::remove($id);
		self::return_back('/products');
	}
}