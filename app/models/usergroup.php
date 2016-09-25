<?php
class Usergroup extends DataModelCreatedBy implements DataTable{
	public $id, $name, $created_by,
			$users,
			$allow_remove;
	public static function get_table_name(){ return 'usergroup'; }
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	
	public static function all(){
		$users = User::all();
		$statement = 'SELECT p.id, p.name, p.created_by,
							(SELECT array_to_string(array_agg(sp.users_id),\',\')
								FROM all_usergroup_users sp
								WHERE sp.usergroup_id = p.id
							) AS user_ids,
							(created_by=:me) AS allow_remove
				FROM '.self::get_table_name().' p
				WHERE p.created_by=:created_by;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':me', LoggedUser::id());
		$query->bindParam(':created_by', LoggedUser::id());
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$user_ids = explode(',', $row['user_ids']);
			$group_users = array();
			foreach ($user_ids as $user){
				if(array_key_exists($user, $users)){
					$group_users[] = $users[$user];
				}
			}
			$row['users'] = $group_users;
			$items[$row['id']] = new Usergroup($row);
		}
		return $items;
	}
	
	public static function get($id){
		//näytetään ryhmä johon käyttäjä kuuluu tai ryhmä jotka käyttäjä on luonut
		return self::_get_by_id($id, 
					' AND (created_by=:created_by 
							OR id IN(SELECT usergroup_id 
									FROM '.UsergroupUsers::get_table_name().' 
									WHERE users_id=:users_id)
							)',
					array('created_by'=>LoggedUser::id(), 
							'users_id'=>LoggedUser::id()
					)
		);
	}
	
	public static function update($cols, $id){
		//sallitaan päivitys vain jos ryhmä on tehty itse tai on ryhmän jäsen
		self::_update_by_id($cols, 
							$id, 
							' AND (id IN(SELECT usergroup_id 
										FROM '.UsergroupUsers::get_table_name().'
										WHERE users_id=:users_id
										)
									OR created_by=:my_id
									)',
							array('created_by' => LoggedUser::id())
		);
	}
	
	public static function add($cols){
		return self::_insert_my($cols);
	}
	
	public static function remove($id){
		self::_remove_my($id);
	}
	
}