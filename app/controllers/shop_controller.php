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
	public static function shop($id){
		$shop = Shop::get($id);
		$products = ShopProduct::products_in_shop($id);
		View::make('shops/shop.html', 
					array('shop' => $shop, 
						'shop_products' => $products, 
						'visibility' => CssClass::visibility()
					)
		);
	}
	
	public static function create_new_form(){
		$usergroups = Usergroup::all();
		View::make('shops/new_shop.html', array('usergroups' => $usergroups));
	}
	
	public static function create_new(){
		if(isset($_POST['name'])){
			$shop = new Shop(array('name' => $_POST['name']));
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
			self::return_back('/shops/shop/'.$shop->id);
		}
		self::return_back('/shops');
	}
	
	public static function edit($id){
		if(isset($_POST['name'])){
			$shop = new Shop(array('name' => $_POST['name'], 'id' => $id));
			$shop->update();
		}
		self::return_back('/shops/shop/'.$id);
	}
	
	public static function remove($id){
		Shop::remove($id);
		self::return_back('/shops');
	}
}