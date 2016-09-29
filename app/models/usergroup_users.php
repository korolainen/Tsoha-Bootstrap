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
		$query->execute(array('my_id'=>LoggedUser::id(), 'my_id_usergroup'=>LoggedUser::id()));
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$items[] = new UsergroupUsers($row);
		}
		return $items;
	}
	
	public static function usergroups($user_id){
		return self::execute(self::statement('WHERE ug.users_id=:user_id'), 
							array('me'=>LoggedUser::id(), 'user_id'=>$user_id), 
							'usergroup_id');
	}
	
	public static function users($usergroup_id){
		return self::execute(self::statement('WHERE ug.usergroup_id=:usergroup_id'),
							array('me'=>LoggedUser::id(), 'usergroup_id'=>$usergroup_id),
							'users_id');
	}
	
	public static function insert($usergroup_id, $users_id){
		$query = DB::connection()->prepare('INSERT INTO usergroup_users(usergroup_id,users_id) 
											VALUES(:usergroup_id,:users_id);');
		$query->execute(array('usergroup_id'=>$usergroup_id, 'users_id'=>$users_id));
	}
	
	public static function remove($usergroup_id, $users_id){
		$query = DB::connection()->prepare('DELETE FROM usergroup_users 
											WHERE usergroup_id=:usergroup_id 
												AND users_id=:users_id;');
		$query->execute(array('usergroup_id' => $usergroup_id, 'users_id' => $users_id));
	}
	
	
	
	
	


	private static function statement($extra = ''){
		return 'SELECT ug.usergroup_id,
						ug.users_id,
						ug.name,
						u.first_name,
						u.last_name,
						(ug.users_id=:me) AS is_my_id
					FROM all_usergroup_users ug
					JOIN users u ON u.id = ug.users_id '.$extra.';';
	}
	private static function execute($statement, $params, $key){
		$query = DB::connection()->prepare($statement);
		$query->execute($params);
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$items[$row[$key]] = new UsergroupUsers($row);
		}
		return $items;
	}
}