<?php
/*
Listaus-, esittely- ja lisäysnäkymä

olion lisääminen tietokantaan käyttäjän lähettämän lomakkeen tiedoill
*/

class ShopController extends BaseController{


	public static function shops(){
		self::check_logged_in();
		$shops = Shop::all();
		View::make('shops/shops.html', array('shops' => $shops));
	}
	
	public static function shop(){
		self::check_logged_in();
		View::make('shops/shop.html');
	}
}