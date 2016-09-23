<?php
class ShoppinglistUsergroup extends DataModelCreatedBy implements DataTable{
	public $shoppinglist_id, $usergroup_id, $created_by;
	public static function get_table_name(){ return 'shoppinglist_usergroup'; }
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	
	public static function all($usergroup_id, $keys){
		$keys = Query_Helper::prefix_array_keys($keys, 'b');
		$sql = self::_select_execute('SELECT '.implode(',', $keys).'
									FROM '.self::get_table_name().' a
									JOIN '.Shoppinglist::get_table_name().' b ON b.id = a.shoppinglist_id
									JOIN '.Usergroup::get_table_name().' d ON d.id = a.usergroup_id
									WHERE (b.created_by=:my_id 
										OR b.usergroup_id IN(SELECT c.usergroup_id 
															FROM '.UsergroupUsers::get_table_name().' c
															WHERE c.users_id = :my_id_c
															)
										OR d.created_by=:my_id_d)
										AND a.usergroup_id=:usergroup_id',
									array('my_id' => LoggedUser::id(),
											'my_id_c' => LoggedUser::id(),
											'my_id_d' => LoggedUser::id(),
										'usergroup_id' => $usergroup_id
									)
		);
		return Query_Helper::build_items($sql, 'Shoppinglist');
	}
	
	public static function insert($usergroup_id, $users_id){
		self::_insert_my(array('usergroup_id'=>$usergroup_id,'users_id'=>$users_id));
	}
	
	public static function remove($id){
		self::_remove_my($id);
	}
}