<?php
class UsergroupUsers extends DataModel implements DataTable{
	public $usergroup_id, $users_id;
	public static function get_table_name(){ return 'usergroup_users'; }
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	
	public static function all($usergroup_id, $keys){
		$keys = Query_Helper::prefix_array_keys($keys, 'b');
		$sql = self::_select_execute('SELECT '.implode(',', $keys).'
										FROM '.self::get_table_name().' a
										JOIN '.User::get_table_name().' b ON b.id = a.users_id
										WHERE b.created_by=:my_id 
											OR a.usergroup_id=:usergroup_id',
										array('my_id' => LoggedUser::id(),
											'usergroup_id' => $usergroup_id
										)
		);
		return Query_Helper::build_items($sql, 'User');
	}
	
	public static function insert($usergroup_id, $users_id){
		self::_insert(array('usergroup_id'=>$usergroup_id,'users_id'=>$users_id));
	}
	
	public static function remove($id){
		self::_remove_by_id($id, array('created_by' => LoggedUser::id()));
	}
}