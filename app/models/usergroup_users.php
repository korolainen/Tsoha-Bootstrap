<?php
class UsergroupUsers extends BaseModel{
	public $usergroup_id, $users_id;
	public function __construct($attributes = null){
		parent::__construct($attributes);
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
		$sql = $query->execute();
		while($row = $sql->fetch(PDO::FETCH_ASSOC)){
			$items[] = new UsergroupUsers($row);
		}
		return $items;
	}
	
	public static function usergroups($user_id){
		$query = DB::connection()->prepare('SELECT usergroup_id, 
											users_id
										FROM all_usergroup_users 
										WHERE users_id=:user_id;');
		$query->bindParam(':user_id', $user_id);
		$sql = $query->execute();
		while($row = $sql->fetch(PDO::FETCH_ASSOC)){
			$items[] = new UsergroupUsers($row);
		}
		return $items;
	}
	
	public static function users($usergroup_id){
		$query = DB::connection()->prepare('SELECT usergroup_id,
											users_id
										FROM all_usergroup_users 
										WHERE usergroup_id=:usergroup_id;');
		$query->bindParam(':usergroup_id', $usergroup_id);
		$sql = $query->execute();
		while($row = $sql->fetch(PDO::FETCH_ASSOC)){
			$items[] = new UsergroupUsers($row);
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