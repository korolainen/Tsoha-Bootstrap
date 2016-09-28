<?php
class Usergroup extends BaseModel{
	public $id, $name, $created_by,
			$users,
			$allow_remove;
	public function __construct($attributes = null){
		parent::__construct($attributes);
		$this->validators = array('validate_name');
	}
	/*
	public function build_html(){
		$this->name = CheckData::character_escape($this->name);
	}
	*/
	
	public static function all(){
		$users = User::get_users_i_know();
		$statement = 'SELECT p.id, p.name, p.created_by,
							(SELECT array_to_string(array_agg(sp.users_id),\',\')
								FROM all_usergroup_users sp
								WHERE sp.usergroup_id = p.id
							) AS user_ids,
							(created_by=:me) AS allow_remove
				FROM usergroup p
				WHERE p.created_by=:created_by;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':me', LoggedUser::id());
		$query->bindParam(':created_by', LoggedUser::id());
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
			$usergroup = new Usergroup($row);
			//$usergroup->build_html();
			$items[$row['id']] = $usergroup; 
		}
		return $items;
	}
	
	public static function find($name){
		$users = User::get_users_i_know();
		$statement = 'SELECT p.id, p.name, p.created_by,
							(SELECT array_to_string(array_agg(sp.users_id),\',\')
								FROM all_usergroup_users sp
								WHERE sp.usergroup_id = p.id
							) AS user_ids,
							(created_by=:me) AS allow_remove
				FROM usergroup p
				WHERE p.created_by=:created_by AND LOWER(p.name) LIKE :name;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':me', LoggedUser::id());
		$query->bindParam(':created_by', LoggedUser::id());
		$name = strtolower($name).'%';
		$query->bindParam(':name', $name);
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
			$usergroup = new Usergroup($row);
			//$usergroup->build_html();
			$items[$row['id']] = $usergroup;
		}
		return $items;
	}
	
	public static function get($id){
		$users = User::get_users_i_know();
		$statement = 'SELECT p.id, p.name, p.created_by,
							(SELECT array_to_string(array_agg(sp.users_id),\',\')
								FROM all_usergroup_users sp
								WHERE sp.usergroup_id = p.id
							) AS user_ids,
							(created_by=:me) AS allow_remove
				FROM usergroup p
				WHERE p.created_by=:created_by AND p.id=:id;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':me', LoggedUser::id());
		$query->bindParam(':created_by', LoggedUser::id());
		$query->bindParam(':id', $id);
		$query->execute();
		$items = array();
		if($row = $query->fetch(PDO::FETCH_ASSOC)){
			$user_ids = explode(',', $row['user_ids']);
			$group_users = array();
			foreach ($user_ids as $user){
				if(array_key_exists($user, $users)){
					$group_users[] = $users[$user];
				}
			}
			$row['users'] = $group_users;
			return new Usergroup($row);
		}
		return null;
	}
	
	public function update(){
		$statement = 'UPDATE usergroup
					SET name=:name
					WHERE id=:id
						AND id IN(SELECT usergroup_id
							FROM all_usergroup_users
							WHERE users_id=:users_id);';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':name', $this->name);
		$query->bindParam(':id', $this->id);
		$query->bindParam(':users_id', LoggedUser::id());
		$query->execute();
	}
	
	public function save(){
		$statement = 'INSERT INTO usergroup(name,created_by) VALUES(:name,:created_by) RETURNING id;';
		$query = DB::connection()->prepare($statement);
		$query->execute(array('name'=>$this->name, 'created_by'=>LoggedUser::id()));
		$row = $query->fetch();
		$this->id = $row['id'];
		return $this->id;
	}
	
	public static function remove($id){
		$statement = 'DELETE FROM usergroup WHERE id=:id AND created_by=:created_by;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':id', $id);
		$query->bindParam(':created_by', LoggedUser::id());
		$query->execute();
	}
	
}