<?php

class Shop extends BaseModel{
	public $id, $name, $created_by,
			$usergroup_id,
			$allow_remove;
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
				FROM shop p
				WHERE p.id IN(SELECT su.shop_id
								FROM shop_users su
								WHERE su.users_id=:users_id)
				ORDER BY p.name ASC;';
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
		$statement = 'SELECT p.id, p.name, p.created_by,
							(SELECT b.usergroup_id
								FROM shop_usergroup b
								WHERE b.shop_id = p.id
								LIMIT 1
							) AS usergroup_id,
							(p.created_by=:me) AS allow_remove
				FROM shop p
				WHERE p.id IN(SELECT su.shop_id
								FROM shop_users su
								WHERE su.users_id=:users_id)
						AND p.id=:id;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':me', LoggedUser::id());
		$query->bindParam(':users_id', LoggedUser::id());
		$query->bindParam(':id', $id);
		$query->execute();
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return new Shop($row);
	}
	
	public function update(){
		$statement = 'UPDATE shop
					SET name=:name
					WHERE id=:id 
						AND id IN(SELECT shop_id
							FROM shop_users
							WHERE users_id=:users_id);';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':name', $this->name);
		$query->bindParam(':id', $this->id);
		$query->bindParam(':users_id', LoggedUser::id());
		$query->execute();
	}
	
	
	public static function remove($id){
		$statement = 'DELETE FROM shop WHERE id=:id AND created_by=:created_by;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':id', $id);
		$query->bindParam(':created_by', LoggedUser::id());
		$query->execute();
	}


	public function save(){
		$statement = 'INSERT INTO shop(name, created_by) VALUES(:name, :created_by) RETURNING id;';
		$query = DB::connection()->prepare($statement);
		$query->execute(array('name'=>$this->name, 'created_by'=>LoggedUser::id()));
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$this->id = $row['id'];
		return $this->id;
	}
	
}