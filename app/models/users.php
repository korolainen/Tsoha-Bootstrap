<?php
class UserModel extends BaseModel{
	public $id, $account, $first_name, $last_name, $phone, $hash, $password, $password_check;
	public $exists = false;
	public function __construct($attributes = null){
		parent::__construct($attributes);
		if(isset($attributes['id'])) $this->exists = true;
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
		return $this->validation_pattern(User::account_exists($this->account), 
										'Tunnus "'.CheckData::character_escape($this->account).'" on olemassa!');
	}
	public function check_email(){
		return $this->validation_pattern((!filter_var($this->account, FILTER_VALIDATE_EMAIL)), 
										'Anna sähköpostimuotoinen käyttäjätunnus!');
	}
	public function check_password_length(){
		return $this->validate_string($this->password, 5, 'Salasana');
	}
	public function check_password2(){
		return $this->validation_pattern(($this->password!=$this->password_check),
										'Salasanankentät eivät vastaa toisiaan!');
	}

	public static function check_account_id($account){
		self::account_exists($account);
		if(isset(self::$existing_account_id[$account])) return self::$existing_account_id[$account];
		return null; 
	}
	private static $existing_accounts = array();
	private static $existing_account_id = array();
	public static function account_exists($account){
		if(isset(self::$existing_accounts[$account])) return self::$existing_accounts[$account];
		$statement = 'SELECT id FROM users WHERE account LIKE :account LIMIT 1;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':account', $account);
		$query->execute();
		self::$existing_accounts[$account] = false;
		if($row = $query->fetch(PDO::FETCH_ASSOC)){
			self::$existing_accounts[$account] = true;
			self::$existing_account_id[$account] = $row['id'];
		}
		return self::$existing_accounts[$account];
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
	private static $users_i_know = null;
	public function get_users_i_know(){
		if(!is_null(self::$users_i_know)) return self::$users_i_know;
		$statement = self::statement();
		$query = self::query($statement);
		$query->execute();
		self::$users_i_know = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			self::$users_i_know[$row['id']] = new User($row);
		}
		return self::$users_i_know;
	}
	public static function get_user_i_know($id){
		$statement = self::statement('AND id=:id');
		$query = self::query($statement);
		$query->bindParam(':id', $id);
		$query->execute();
		$item = array();
		return new User($query->fetch(PDO::FETCH_ASSOC));
	}

	public static function user_i_know_exists($id){
		$user = self::get_user_i_know($id);
		return $user->exists;
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
		return new User($query->fetch(PDO::FETCH_ASSOC));
	}
	
	
	



	private static function statement($extra = ''){
		return 'SELECT p.id, p.account, p.first_name, p.last_name, p.phone
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
						) '.$extra.';';
	}
	
	private static function query($statement){
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':shoppinglist_users_id', LoggedUser::id());
		$query->bindParam(':shop_users_id', LoggedUser::id());
		return $query;
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
		$statement = 'UPDATE users SET last_name=:last_name, first_name=:first_name, phone=:phone ';
		if(!empty($this->hash)) $statement .= ', hash=:hash ';
		$statement .= ' WHERE id=:id;';
		$query = DB::connection()->prepare($statement);
		$params = array('last_name'=>$this->last_name, 
						'first_name'=>$this->first_name, 
						'phone'=>$this->phone);
		if(!empty($this->hash)) $params['hash'] = $this->hash;
		$params['id'] = LoggedUser::id();
		$query->execute($params);
		if(!empty($this->hash)){
			LoggedUser::set_user_data(array('id' => LoggedUser::id(), 'hash' => $this->hash));
		}
	}
	
	public static function remove(){
		$query = DB::connection()->prepare('DELETE FROM users WHERE id=:id;');
		$query->execute(array('id' => LoggedUser::id()));
	}
	
	public static function get_by_secure_key($key){
		$query = DB::connection()->prepare('SELECT id, account, first_name, last_name, phone, hash
											FROM users
											WHERE MD5(id::text || SUBSTRING(hash, 33)) LIKE :key
											LIMIT 1;');
		$query->execute(array('key' => $key));
		return new Me($query->fetch(PDO::FETCH_ASSOC));
	}
}