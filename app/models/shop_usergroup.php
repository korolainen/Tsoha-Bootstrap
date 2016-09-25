<?php
class ShopUsergroup extends DataModelCreatedBy implements DataTable{
	public $usergroup_id, $shop_id, $created_by;
	public static function get_table_name(){ return 'shop_usergroup'; }
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	
	public static function all(){
		$sql = self::_select_execute('SELECT a.usergroup_id, a.shop_id, a.created_by,
											CONCAT(a.usergroup_id, \'-\', a.shop_id) AS id
									FROM '.self::get_table_name().' a
									WHERE (a.created_by=:my_id 
										OR a.usergroup_id IN(SELECT b.usergroup_id 
															FROM shop_users b
															WHERE b.shop_id = a.shop_id
															)
										)
										AND a.usergroup_id=:usergroup_id',
									array('my_id' => LoggedUser::id(),
										'usergroup_id' => $usergroup_id
									)
		);
		return Query_Helper::build_items($sql, 'ShopUsergroup');
	}
	
	public static function shops($usergroup_id){
		$sql = self::_select_execute('SELECT a.usergroup_id, a.shop_id, a.created_by,
											CONCAT(a.usergroup_id, \'-\', a.shop_id) AS id
									FROM '.self::get_table_name().' a
									WHERE (a.created_by=:my_id 
										OR a.usergroup_id IN(SELECT b.usergroup_id 
															FROM shop_users b
															WHERE b.shop_id = a.shop_id
															)
										)
										AND a.usergroup_id=:usergroup_id',
									array('my_id' => LoggedUser::id(),
										'usergroup_id' => $usergroup_id
									)
		);
		return Query_Helper::build_items($sql, 'ShopUsergroup');
	}
	
	public static function usergroups($shop_id){
		$sql = self::_select_execute('SELECT a.usergroup_id, a.shop_id, a.created_by,
											CONCAT(a.usergroup_id, \'-\', a.shop_id) AS id
									FROM '.self::get_table_name().' a
									WHERE (a.created_by=:my_id 
										OR a.usergroup_id IN(SELECT b.usergroup_id 
															FROM shop_users b
															WHERE b.shop_id = a.shop_id
															)
										)
										AND a.shop_id=:shop_id',
									array('my_id' => LoggedUser::id(),
										'shop_id' => $shop_id
									)
		);
		return Query_Helper::build_items($sql, 'ShopUsergroup');
	}
	
	public static function insert($usergroup_id, $users_id){
		self::_insert_my(array('usergroup_id'=>$usergroup_id,'shop_id'=>$users_id));
	}
	
	public static function remove($id){
		self::_remove_my($id);
	}
}