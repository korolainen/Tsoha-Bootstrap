<?php
class UsergroupUsers extends BaseModel{
	public $usergroup_id, $users_id, $last_name, $first_name, $is_my_id;
	public function __construct($attributes = null){
		parent::__construct($attributes);
		$this->validators = array('validate_first_name');
	}
	
	public static function all(){
		$query = DB::connection()->prepare('SELECT a.usergroup_id, a.users_id
										FROM usergroup_users a
										JOIN users b ON b.id = a.users_id
										WHERE b.created_by=:my_id 
											OR a.usergroup_id IN(SELECT auu.usergroup_id
																FROM all_usergroup_users auu
																WHERE users_id=:my_id_usergroup);');
		$query->bindParam(':my_id', LoggedUser::id());
		$query->bindParam(':my_id_usergroup', LoggedUser::id());
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$items[] = new UsergroupUsers($row);
		}
		return $items;
	}
	
	public static function usergroups($user_id){
		$query = DB::connection()->prepare('SELECT ug.usergroup_id, 
											ug.users_id,
											u.name
										FROM all_usergroup_users ug
										JOIN usergroup u ON u.id = ug.usergroup_id
										WHERE ug.users_id=:user_id;');
		$query->bindParam(':user_id', $user_id);
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$items[$row['usergroup_id']] = new UsergroupUsers($row);
		}
		return $items;
	}
	
	public static function users($usergroup_id){
		$query = DB::connection()->prepare('SELECT ug.usergroup_id,
												ug.users_id,
												u.first_name, 
												u.last_name,
												(ug.users_id=:me) AS is_my_id
											FROM all_usergroup_users ug
											JOIN users u ON u.id = ug.users_id 
											WHERE ug.usergroup_id=:usergroup_id;');
		$query->bindParam(':usergroup_id', $usergroup_id);
		$query->bindParam(':me', LoggedUser::id());
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$items[$row['users_id']] = new UsergroupUsers($row);
		}
		return $items;
	}
	
	public static function insert($usergroup_id, $users_id){
		$statement = 'INSERT INTO usergroup_users(usergroup_id,users_id) VALUES(:usergroup_id,:users_id);';
		$query = DB::connection()->prepare($statement);
		$query->execute(array('usergroup_id'=>$usergroup_id, 'users_id'=>$users_id));
	}
	
	public static function remove($usergroup_id, $users_id){
		$statement = 'DELETE FROM usergroup_users WHERE usergroup_id=:usergroup_id AND users_id=:users_id;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':usergroup_id', $usergroup_id);
		$query->bindParam(':users_id', $users_id);
		$query->execute();
	}
}