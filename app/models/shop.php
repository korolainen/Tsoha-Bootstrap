<?php

class Shop extends DataModelCreatedBy implements DataTable{
	public $id, $name, $created_by,
			$usergroup,
			$allow_remove;
	public static function get_table_name(){ return 'shop'; }
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	
	public static function all(){
		$usergroups = Usergroup::all();
		$statement = 'SELECT p.id, p.name, p.created_by,
							(SELECT b.usergroup_id 
								FROM shop_usergroup b
								WHERE b.shop_id = p.id
								LIMIT 1
							) AS usergroup_id,
							(p.created_by=:me) AS allow_remove
				FROM '.self::get_table_name().' p
				WHERE p.id IN(SELECT su.shop_id
								FROM shop_users su
								WHERE su.users_id=:users_id);';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':me', LoggedUser::id());
		$query->bindParam(':users_id', LoggedUser::id());
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			if(array_key_exists($row['usergroup_id'], $usergroups)){
				$row['usergroup'] = $usergroups[$row['usergroup_id']];
			}
			$items[$row['id']] = new Shop($row);
		}
		return $items;
	}

	public static function get($id){
		return self::_get_by_id($id,
				' AND id IN(SELECT shop_id
							FROM shop_users
							WHERE users_id=:users_id)',
				array('users_id'=>LoggedUser::id())
		);
	}
	
	public static function update($cols, $id){
		self::_update_by_id($cols, 
							$id, 
							' AND id IN(SELECT shop_id
										FROM shop_users
										WHERE users_id=:users_id)',
							array('users_id' => LoggedUser::id())
		);
	}
	
	public static function remove($id){
		self::_remove_my($id);
	}
	
	public static function add($cols){
		return self::_insert_my($cols);
	}
	
}