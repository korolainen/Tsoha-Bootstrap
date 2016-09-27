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
	public static function all(){
		return self::_all();
	}

	public static function check_account($account){
		$statement = 'SELECT id FROM users WHERE account LIKE :account LIMIT 1;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':account', $account);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC);
	}
	public static function get($id){
		$item = self::_get(array('id'=>$id));
		if(empty($item)) return null; 
		return current($item);
	}
	public static function add($row){
		return self::_insert($row);
	}
	public static function get_by_account_and_pass($username, $password){
		$statement = 'SELECT id, hash
                    	FROM '.self::get_table_name().'
                    	WHERE account LIKE :account 
                    		AND SUBSTRING(hash, 1, 32)=MD5(CONCAT(:password, SUBSTRING(hash, 33))) 
				            LIMIT 1;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':account', $username);
		$query->bindParam(':password', $password);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC);
	}
}
class Me extends UserModel implements DataTable{
	public function __construct($attributes){
		parent::__construct($attributes);
	}
	public static function get(){
		return self::_get_by_id(LoggedUser::id());
	}
	public static function update($cols){
		self::_update_by_id($cols, LoggedUser::id());
	}
	public static function remove(){
		self::_remove_by_id(LoggedUser::id());
	}
	public static function get_by_secure_key($key){
		$statement = 'SELECT id, account, first_name, last_name, phone, hash
						FROM '.self::get_table_name().'
						WHERE MD5(id::text || SUBSTRING(hash, 33)) LIKE :key
						LIMIT 1;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':key', $key);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC);
	}
}