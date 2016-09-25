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
	
	public static function shop(){
		View::make('shops/shop.html');
	}
	
	public static function create_new(){
		
	}
	
	public static function edit(){
		
	}
	
	public static function remove(){
		
	}
}