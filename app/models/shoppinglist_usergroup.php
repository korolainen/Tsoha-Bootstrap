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
	
	public static function insert($shoppinglist_id, $usergroup_id){
		$statement = 'INSERT INTO shoppinglist_usergroup(usergroup_id,shoppinglist_id,created_by) 
					VALUES(:usergroup_id,:shoppinglist_id,:created_by);';
		$query = DB::connection()->prepare($statement);
		$query->execute(array('usergroup_id'=>$usergroup_id, 'shoppinglist_id'=>$shoppinglist_id, 'created_by'=>LoggedUser::id()));
	}
	
	public static function remove($shoppinglist_id, $usergroup_id){
		$statement = 'DELETE FROM shoppinglist_usergroup WHERE shoppinglist_id=:shoppinglist_id 
															AND usergroup_id=:usergroup_id
															AND created_by=:created_by;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':shoppinglist_id', $shoppinglist_id);
		$query->bindParam(':usergroup_id', $usergroup_id);
		$query->bindParam(':created_by', LoggedUser::id());
		$query->execute();
	}
}