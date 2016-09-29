<?php
class Shoppinglist extends BaseModel{
	public $id, $name, $created_by, $active,
			$is_active,
			$active_date,
			$usergroup,
			$current,
			$created_by_me;
	public function __construct($attributes = null){
		parent::__construct($attributes);
		$this->validators = array('validate_name', 'validate_active');
	}
	public static function all(){
		$statement = self::statement();
		$query = self::query($statement);
		return self::execute($query);
	}

	public static function users($shoppinglist_id){
		$query = DB::connection()->prepare('SELECT u.id, u.account, u.first_name, u.last_name, u.phone, u.hash
											FROM users u
											JOIN shoppinglist_users su ON su.users_id=u.id 
																		AND su.shoppinglist_id=:shoppinglist_id
											WHERE su.users_id!=:me;');
		$query->execute(array('shoppinglist_id'=>$shoppinglist_id,'me' => LoggedUser::id()));
		$item = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$item[$row['id']] = new User($row);
		}
		return $item;
	}
	
	public static function find($name){
		$statement = self::statement('AND LOWER(p.name) LIKE :name');
		$query = self::query($statement);
		$name = strtolower($name).'%';
		$query->bindParam(':name', $name);
		return self::execute($query);
	}

	public static function get($id){
		$usergroups = Usergroup::all();
		$statement = self::statement('AND p.id=:id');
		$query = self::query($statement);
		$query->bindParam(':id', $id);
		$items = self::execute($query);
		if(count($items)<=0) return null;
		return current($items);
	}

	public function update(){
		$statement = 'UPDATE shoppinglist
					SET name=:name, active=:active
					WHERE id=:id 
						AND id IN(SELECT shoppinglist_id
							FROM shoppinglist_users
							WHERE users_id=:users_id);';
		$query = DB::connection()->prepare($statement);
		$query->execute(array('name'=>$this->name,'active' => $this->active,
								'id'=>$this->id,'users_id' => LoggedUser::id()));
	}
	
	public function save(){
		$statement = 'INSERT INTO shoppinglist(name, active, created_by) 
						VALUES(:name, :active, :created_by)
					RETURNING id;';
		$query = DB::connection()->prepare($statement);
		$query->execute(array('name'=>$this->name, 
				'active'=>$this->active, 
				'created_by'=>LoggedUser::id()));
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$this->id = $row['id'];
		return $this->id;
	}
	
	public static function remove($id){
		$query = DB::connection()->prepare('DELETE FROM shoppinglist WHERE id=:id AND created_by=:created_by;');
		$query->execute(array('id'=>$id,'created_by' => LoggedUser::id()));
	}
	
	
	
	
	
	
	

	private static function statement($extra = ''){
		return 'SELECT p.id, p.name, p.active, p.created_by,
							(SELECT b.usergroup_id
								FROM shoppinglist_usergroup b
								WHERE b.shoppinglist_id = p.id
								LIMIT 1
							) AS usergroup_id,
							(p.created_by=:me) AS created_by_me,
							(DATE \'tomorrow\' > NOW()) AS is_active
				FROM shoppinglist p
				WHERE p.id IN(SELECT su.shoppinglist_id
								FROM shoppinglist_users su
								WHERE su.users_id=:users_id)
				'.$extra.'
				ORDER BY active DESC;';
	}
	private static function query($statement){
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':me', LoggedUser::id());
		$query->bindParam(':users_id', LoggedUser::id());
		return $query;
	}
	private static function execute($query){
		$usergroups = Usergroup::all();
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			if(array_key_exists($row['usergroup_id'], $usergroups)){
				$row['usergroup'] = $usergroups[$row['usergroup_id']];
			}
			$row['active_date'] = date('d.m.Y',strtotime($row['active']));
			$row['current'] = $row['is_active']=='1' ? 'current-shoppinglist-row' : 'old-shoppinglist-row';
			$items[$row['id']] = new Shoppinglist($row);
		}
		return $items;
	}
}