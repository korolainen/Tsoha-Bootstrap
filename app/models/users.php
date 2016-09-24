<?php
class UserModel extends DataModel implements DataTable{
	public $id, $account, $first_name, $last_name, $phone, $hash;
	public static function get_table_name(){ return 'users'; }
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
}
class User extends UserModel implements DataTable{
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	public static function get($id){
		return self::_get(array('id'=>$id));
	}
	public static function add($row){
		return self::_insert($row);
	}
	public static function get_by_account_and_pass(){
		return null;
	}
}
class Me extends UserModel implements DataTable{
	public function __construct($attributes){
		parent::__construct($attributes);
	}
	public static function get(){
		return User::get(LoggedUser::id());
	}
	public static function update($cols){
		self::_update_by_id($cols, LoggedUser::id());
	}
	public static function remove(){
		self::_remove_by_id(LoggedUser::id());
	}
	public static function get_by_secure_key(){
		return null;
	}
}