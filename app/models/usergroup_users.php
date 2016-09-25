<?php
class UsergroupUsers extends DataModel implements DataTable{
	public $usergroup_id, $users_id;
	public static function get_table_name(){ return 'usergroup_users'; }
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	
	public static function all(){
		$sql = self::_select_execute('SELECT a.usergroup_id, a.users_id, 
											CONCAT(a.usergroup_id, \'-\', a.users_id) AS id
										FROM '.self::get_table_name().' a
										JOIN '.User::get_table_name().' b ON b.id = a.users_id
										WHERE b.created_by=:my_id 
											OR a.usergroup_id IN(SELECT auu.usergroup_id
																FROM all_usergroup_users auu
																WHERE users_id=:my_id_usergroup)',
										array('my_id' => LoggedUser::id(),
											'my_id_usergroup' => LoggedUser::id()
										)
		);
		return Query_Helper::build_items($sql, 'UsergroupUsers');
	}
	
	public static function usergroups($user_id){
		$sql = self::_select_execute('SELECT usergroup_id, 
											users_id, 
											CONCAT(usergroup_id, \'-\', users_id) AS id
										FROM all_usergroup_users 
										WHERE users_id=:user_id',
										array('user_id' => $user_id)
		);
		return Query_Helper::build_items($sql, 'UsergroupUsers');
	}
	
	public static function users($usergroup_id){
		$sql = self::_select_execute('SELECT usergroup_id, 
											users_id, 
											CONCAT(usergroup_id, \'-\', users_id) AS id
										FROM all_usergroup_users 
										WHERE usergroup_id=:usergroup_id',
										array('usergroup_id' => $usergroup_id)
		);
		return Query_Helper::build_items($sql, 'UsergroupUsers');
	}
	
	public static function insert($usergroup_id, $users_id){
		self::_insert(array('usergroup_id'=>$usergroup_id,'users_id'=>$users_id));
	}
	
	public static function remove($id){
		self::_remove_by_id($id, array('created_by' => LoggedUser::id()));
	}
}