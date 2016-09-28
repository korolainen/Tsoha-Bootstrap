<?php
class Messages{
	public static function errors(){
		$errors_json = Session::pop('errors');
		if(!empty($errors_json)){
			return json_decode($errors_json);
		}
		return array();
	}
}