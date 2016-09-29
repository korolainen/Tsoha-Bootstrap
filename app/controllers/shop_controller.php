<?php
/*
Listaus-, esittely- ja lisäysnäkymä

olion lisääminen tietokantaan käyttäjän lähettämän lomakkeen tiedoill
*/

class ShopController extends BaseController{


	public static function shops(){
		$shops = Shop::all();
		View::make('shops/shops.html', array('shops' => $shops));
	}
	public static function find(){
		if(!isset($_GET['q'])) exit();
		$shops = Shop::find($_GET['q']);
		View::make('shops/find.html', array('shops' => $shops));
	}
	public static function link($shop_id){
		if(!isset($_GET['q'])) exit();
		$products = Product::find_not_in_shop($_GET['q'], $shop_id);
		View::make('products/linkproduct.html', array('products' => $products));
	}
	public static function shop($id){
		$shop = Shop::get($id);
		$products = ShopProduct::products_in_shop($id);
		View::make('shops/shop.html', 
					array('shop' => $shop, 
						'shop_products' => $products, 
						'visibility' => CssClass::visibility(),
						'errors' => Messages::errors(),
						'attributes' => Session::pop('attributes')
					)
		);
	}
	
	public static function create_new_form(){
		$usergroups = Usergroup::all();
		View::make('shops/new_shop.html', array('usergroups' => $usergroups, 
												'errors' => Messages::errors(),
												'attributes' => Session::pop('attributes')));
	}
	
	public static function create_new(){
		CheckPost::required_redirect(array('name'), '/shops');
		$shop = new Shop(array('name' => $_POST['name']));
		$shop->check_errors_and_redirect('/shops/new');
		$shop_id = $shop->save();
		if(isset($_POST['group'])){
			$usergroups = array();
			foreach($_POST['group'] as $usergroup_id){
				if(isset($usergroups[$usergroup_id])) continue;
				$usergroups[$usergroup_id] = $usergroup_id;
				$usergroup = Usergroup::get($usergroup_id);
				if(empty($usergroup)) continue;
				ShopUsergroup::insert($shop_id, $usergroup->id);
			}
		}
		Redirect::back('/shops/shop/'.$shop->id);
	}
	
	public static function edit($id){
		CheckPost::required_redirect(array('name'), '/shops/shop/'.$id);
		$shop = new Shop(array('name' => $_POST['name'], 'id' => $id));
		$shop->check_errors_and_redirect('/shops/shop/'.$id.'?edit=true');
		$shop->update();
		Redirect::back('/shops/shop/'.$id);
	}
	
	public static function remove($id){
		Shop::remove($id);
		Redirect::back('/shops');
	}
}