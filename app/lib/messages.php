<?php
class Messages{
	public static function errors(){
		$errors_json = Session::pop('errors');
		if(!empty($errors_json)){
			return json_decode($errors_json);
		}
		return array();
	}

	public static function redirect_errors($errors, $url = null){
		if(!empty($errors)){
			Session::set('errors', json_encode($errors));
			Session::set('attributes', $_POST);
			Redirect::back($url);
		}
	}
	
}