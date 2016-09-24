<?php

class SearchController extends BaseController{


	public static function search(){
		self::check_logged_in();
		View::make('search/search.html');
	}
	
}