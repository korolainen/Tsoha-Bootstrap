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
	/*
	public static function all(){
		return self::_all();
	}
	*/
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
	public function save(){
		$statement = 'INSERT INTO users(account, first_name, last_name, phone, hash) 
						VALUES(:account, :first_name, :last_name, :phone, :hash) RETURNING id;';
		$query = DB::connection()->prepare($statement);
		$query->execute(array('account'=>$this->account, 
							'first_name'=>$this->first_name, 
							'last_name'=>$this->last_name, 
							'phone'=>$this->phone, 
							'hash'=>$this->hash
		));
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$this->id = $row['id'];
		return $this->id;
	}
	public function get_users_i_know(){
		$statement = 'SELECT p.id, p.account, p.first_name, p.last_name, p.phone
						FROM users p
						WHERE (p.id IN(SELECT slp.users_id
										FROM shoppinglist_users slp
										WHERE slp.shoppinglist_id IN(SELECT slu.shoppinglist_id
																FROM shoppinglist_users slu
																WHERE slu.users_id=:shoppinglist_users_id
																)
										)
								 OR p.id IN(SELECT shp.users_id
												FROM shop_users shp
												WHERE shp.shop_id IN(SELECT su.shop_id
																		FROM shop_users su
																		WHERE su.users_id=:shop_users_id
																		)
												)
						)
						LIMIT 1;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':shoppinglist_users_id', LoggedUser::id());
		$query->bindParam(':shop_users_id', LoggedUser::id());
		$query->execute();
		if($row = $query->fetch(PDO::FETCH_ASSOC)){
			return new User($row);
		}
		return null;
	}
	public static function get_by_account_and_pass($username, $password){
		$statement = 'SELECT id, hash
                    	FROM '.self::get_table_name().'
                    	WHERE account LIKE :account 
                    		AND SUBSTRING(hash, 1, 32)=MD5(:password || SUBSTRING(hash, 33)) 
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