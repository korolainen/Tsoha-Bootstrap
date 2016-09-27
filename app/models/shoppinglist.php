<?php
class Shoppinglist extends DataModelCreatedBy implements DataTable{
	public $id, $name, $created_by, $active,
			$is_active,
			$active_date,
			$usergroup,
			$current,
			$allow_remove;
	public static function get_table_name(){ return 'shoppinglist'; }
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	
	public static function all(){
		$usergroups = Usergroup::all();
		$statement = 'SELECT p.id, p.name, p.active, p.created_by,
							(SELECT b.usergroup_id 
								FROM shoppinglist_usergroup b
								WHERE b.shoppinglist_id = p.id
								LIMIT 1
							) AS usergroup_id,
							(p.created_by=:me) AS allow_remove,
							(DATE \'tomorrow\' > NOW()) AS is_active
				FROM '.self::get_table_name().' p
				WHERE p.id IN(SELECT su.shoppinglist_id
								FROM shoppinglist_users su
								WHERE su.users_id=:users_id)
				ORDER BY active DESC;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':me', LoggedUser::id());
		$query->bindParam(':users_id', LoggedUser::id());
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

	public static function get($id){
		$usergroups = Usergroup::all();
		$statement = 'SELECT p.id, p.name, p.active, p.created_by,
							(SELECT b.usergroup_id 
								FROM shoppinglist_usergroup b
								WHERE b.shoppinglist_id = p.id
								LIMIT 1
							) AS usergroup_id,
							(p.created_by=:me) AS allow_remove,
							(DATE \'tomorrow\' > NOW()) AS is_active
				FROM '.self::get_table_name().' p
				WHERE p.id IN(SELECT su.shoppinglist_id
								FROM shoppinglist_users su
								WHERE su.users_id=:users_id)
					AND p.id=:id;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':me', LoggedUser::id());
		$query->bindParam(':users_id', LoggedUser::id());
		$query->bindParam(':id', $id);
		$query->execute();
		if($row = $query->fetch(PDO::FETCH_ASSOC)){
			if(array_key_exists($row['usergroup_id'], $usergroups)){
				$row['usergroup'] = $usergroups[$row['usergroup_id']];
			}
			$row['active_date'] = date('d.m.Y',strtotime($row['active']));
			$row['current'] = $row['is_active']=='1' ? 'current-shoppinglist-row' : 'old-shoppinglist-row'; 
			return new Shoppinglist($row);
		}
		return null;
	}

	public static function update($name, $active, $id){
		$statement = 'UPDATE shoppinglist
					SET name=:name, active=:active
					WHERE id=:id 
						AND id IN(SELECT shoppinglist_id
							FROM shoppinglist_users
							WHERE users_id=:users_id);';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':name', $name);
		$query->bindParam(':active', $active);
		$query->bindParam(':id', $id);
		$query->bindParam(':users_id', LoggedUser::id());
		$query->execute();
	}
	
	public function save(){
		$statement = 'INSERT INTO shoppinglist(name, active, created_by) 
						VALUES(:name, now(), :created_by)
					RETURNING id;';
		$query = DB::connection()->prepare($statement);
		$query->execute(array('name'=>$this->name, 
				//'active'=>$this->active, 
				'created_by'=>LoggedUser::id()));
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$this->id = $row['id'];
		return $this->id;
	}
	
	public static function remove($id){
		$statement = 'DELETE FROM shoppinglist WHERE id=:id AND created_by=:created_by;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':id', $id);
		$query->bindParam(':created_by', LoggedUser::id());
		$query->execute();
	}
}