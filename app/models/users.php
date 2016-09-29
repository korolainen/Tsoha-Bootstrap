<?php
class UserModel extends BaseModel{
	public $id, $account, $first_name, $last_name, $phone, $hash, $password, $password_check;
	public function __construct($attributes = null){
		parent::__construct($attributes);
		$this->validators = array('validate_first_name');
	}
}
class User extends UserModel{
	public function __construct($attributes = null){
		parent::__construct($attributes);
		$this->validators[] = 'check_similar';
		$this->validators[] = 'check_email';
		$this->validators[] = 'check_password_length';
		$this->validators[] = 'check_password2';
	}
	public function check_similar(){
		$errors = array();
		$existing_user = User::check_account($this->account);
		if(!empty($existing_user)){
			$errors[] = 'Tunnus "'.CheckData::character_escape($this->account).'" on olemassa!';
		} 
		return $errors;
	}
	public function check_email(){
		$errors = array();
		if(!filter_var($this->account, FILTER_VALIDATE_EMAIL)) {
			$errors[] = 'Anna sähköpostimuotoinen käyttäjätunnus!';
	    }
		return $errors;
	}
	public function check_password_length(){
		return $this->validate_string($this->password, 5, 'Salasana');
	}
	public function check_password2(){
		$errors = array();
		if($this->password!=$this->password_check) {
			$errors[] = 'Salasanankentät eivät vastaa toisiaan!';
	    }
		return $errors;
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
		$statement = 'SELECT id, account, first_name, last_name, phone, hash
						FROM users
						WHERE id=:id
						LIMIT 1;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':id', $id);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC);
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
						);';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':shoppinglist_users_id', LoggedUser::id());
		$query->bindParam(':shop_users_id', LoggedUser::id());
		$query->execute();
		$item = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$user = new User($row);
			//$user->build_html();
			$item[$row['id']] = $user;
		}
		return $item;
	}
	public function get_user_i_know($id){
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
						) AND id=:id;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':shoppinglist_users_id', LoggedUser::id());
		$query->bindParam(':shop_users_id', LoggedUser::id());
		$query->bindParam(':id', $id);
		$query->execute();
		$item = array();
		if($row = $query->fetch(PDO::FETCH_ASSOC)){
			$item = new User($row);
			//$item->build_html();
			return $item;
		}
		return null;
	}
	/**
	 * https://crackstation.net/hashing-security.htm
	 * Salasanan tallentaminen salt:n avulla
	 * */
	public static function get_by_account_and_pass($username, $password){
		$statement = 'SELECT id, hash
                    	FROM users
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
class Me extends UserModel{
	
	public function __construct($attributes){
		parent::__construct($attributes);
	}
	
	public static function get(){
		return User::get(LoggedUser::id());
	}
	
	public function update(){
		$statement = 'UPDATE users
					SET last_name=:last_name, first_name=:first_name, phone=:phone 
					WHERE id=:id;';
		if(!empty($this->hash)){
			$statement = 'UPDATE users
					SET last_name=:last_name, first_name=:first_name, phone=:phone, hash=:hash
					WHERE id=:id;';
		}
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':last_name', $this->last_name);
		$query->bindParam(':first_name', $this->first_name);
		$query->bindParam(':phone', $this->phone);
		if(!empty($this->hash)){
			$query->bindParam(':hash', $this->hash);
		}
		$query->bindParam(':id', LoggedUser::id());
		$query->execute();
		if(!empty($this->hash)){
			LoggedUser::set_user_data(array('id' => LoggedUser::id(), 'hash' => $this->hash));
		}
	}
	
	public static function remove(){
		$statement = 'DELETE FROM users WHERE id=:id;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':id', LoggedUser::id());
		$query->execute();
	}
	
	public static function get_by_secure_key($key){
		$statement = 'SELECT id, account, first_name, last_name, phone, hash
						FROM users
						WHERE MD5(id::text || SUBSTRING(hash, 33)) LIKE :key
						LIMIT 1;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':key', $key);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC);
	}
}