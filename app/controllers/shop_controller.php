<?php
/*
Listaus-, esittely- ja lisäysnäkymä

olion lisääminen tietokantaan käyttäjän lähettämän lomakkeen tiedoill
*/

class ShopController extends BaseController{


	public static function shops(){
		View::make('shops/shops.html');
	}
	
	public static function shop(){
		View::make('shops/shop.html');
	}
}