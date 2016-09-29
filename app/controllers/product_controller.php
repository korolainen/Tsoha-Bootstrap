<?php
class ProductController extends BaseController{

	public static function products(){
		$products = Product::all();
		View::make('products/products.html', array('products' => $products, 'user'=>Me::get()));
	}
	public static function find(){
		if(!isset($_GET['q'])) exit();
		$products = Product::find($_GET['q']);
		View::make('products/find.html', array('products' => $products));
	}
	public static function product($id){
		$product = Product::get($id);
		CheckPermission::product_object($product);
		$product_shops = ShopProduct::product_in_shops($id);
		$product_not_shops = ShopProduct::product_not_in_shops($id);
		View::make('products/product.html', array('product' => $product, 
													'visibility' => CssClass::visibility(), 
													'product_shops' => $product_shops, 
													'product_not_shops' => $product_not_shops,
													'errors' => Messages::errors()));
	}
	
	public static function create_new_form(){
		View::make('products/new_product.html', array('errors' => Messages::errors()));
	}
	
	public static function create_new(){
		CheckPost::required_redirect(array('name'), '/products');
		$product = new Product(array('name' => $_POST['name']));
		$product->check_errors_and_redirect('/products/new');
		$product_id = $product->save();
		Redirect::back('/products/product/'.$product_id);
	}
	
	public static function edit($id){
		CheckPermission::product($id);
		CheckPost::required_redirect(array('name'), '/products/product/'.$id);
		$product = new Product(array('name' => $_POST['name'], 'id' => $id));
		$product->check_errors_and_redirect('/products/product/'.$id.'?edit=true');
		$product->update();
		Redirect::back('/products/product/'.$id);
	}
	
	public static function remove($id){
		CheckPermission::product($id);
		Product::remove($id);
		Redirect::back('/products');
	}
}