<?php
class Usergroup extends BaseModel{
	public $id, $name, $created_by,
			$users,
			$created_by_me,
			$is_in_shop;
	public function __construct($attributes = null){
		parent::__construct($attributes);
		$this->validators = array('validate_name');
	}
	
	public static function all(){
		$statement = self::statement();
		$query = self::query($statement);
		return self::execute($query);
	}
	
	
	
	public static function all_in_shop($id){
		$statement = 'SELECT p.id, p.name, p.created_by,
							(SELECT array_to_string(array_agg(sp.users_id),\',\')
								FROM all_usergroup_users sp
								WHERE sp.usergroup_id = p.id
							) AS user_ids,
							(su.shop_id IS NOT NULL) AS is_in_shop,
							(p.created_by=:me) AS allow_remove
				FROM usergroup p
				LEFT JOIN shop_usergroup su ON p.id = su.usergroup_id AND su.shop_id=:shop_id
				WHERE p.created_by=:created_by;';
		$query = self::query($statement);
		$query->bindParam(':shop_id', $id);
		return self::execute($query);
	}
	
	public static function find($name){
		$users = User::get_users_i_know();
		$statement = self::statement('AND LOWER(p.name) LIKE :name');
		$query = self::query($statement);
		$name = strtolower($name).'%';
		$query->bindParam(':name', $name);
		return self::execute($query);
	}
	
	public static function get($id){
		$users = User::get_users_i_know();
		$statement = self::statement('AND p.id=:id LIMIT 1');
		$query = self::query($statement);
		$query->bindParam(':id', $id);
		$items = self::execute($query);
		if(count($items)>0) return current($items); 
		return null;
	}
	
	public function update(){
		$query = DB::connection()->prepare('UPDATE usergroup
											SET name=:name
											WHERE id=:id
												AND id IN(SELECT usergroup_id
													FROM all_usergroup_users
													WHERE users_id=:users_id);');
		$query->execute(array('name'=>$this->name, 'id'=>$this->id, 'users_id' => LoggedUser::id()));
	}
	
	public function save(){
		$query = DB::connection()->prepare('INSERT INTO usergroup(name,created_by) 
											VALUES(:name,:created_by) 
											RETURNING id;');
		$query->execute(array('name'=>$this->name, 'created_by'=>LoggedUser::id()));
		$row = $query->fetch();
		$this->id = $row['id'];
		return $this->id;
	}
	
	public static function remove($id){
		$statement = 'DELETE FROM usergroup WHERE id=:id AND created_by=:created_by;';
		$query = self::_query($statement);
		$query->execute(array('id'=>$id,'created_by'=>LoggedUser::id()));
	}
	
	
	
	
	
	
	
	

	private static function statement($where_extra = '', $join = '', $extra_key = ''){
		return 'SELECT p.id, p.name, p.created_by,
							(SELECT array_to_string(array_agg(sp.users_id),\',\')
								FROM all_usergroup_users sp
								WHERE sp.usergroup_id = p.id
							) AS user_ids,
							'.$extra_key.'
							(p.created_by=:me) AS created_by_me
				FROM usergroup p
				'.$join.'
				WHERE p.created_by=:created_by
						'.$where_extra.';';
	}
	
	private static function query($statement){
		return parent::_query($statement, array('me' => LoggedUser::id(), 'created_by' => LoggedUser::id()));
	}
	private static function execute($query){
		$users = User::get_users_i_know();
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$user_ids = explode(',', $row['user_ids']);
			$group_users = array();
			foreach ($user_ids as $user_id){
				if(isset($users[$user_id])){
					$group_users[] = $users[$user_id];
				}
			}
			$row['users'] = $group_users;
			$items[$row['id']] = new Usergroup($row);
		}
		return $items;
	}
	
}