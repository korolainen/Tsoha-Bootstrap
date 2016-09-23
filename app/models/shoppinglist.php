<?php
class Shoppinglist extends DataModelCreatedBy implements DataTable{
	public $name, $created_by, $active;
	public static function get_table_name(){ return 'shoppinglist'; }
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	
	public static function all(){
		return self::_get(null,
						'id IN(SELECT shoppinglist_id
							FROM shoppinglist_users
							WHERE users_id=:users_id)',
						array('users_id'=>LoggedUser::id())
		);
	}

	public static function get($id){
		return self::_get_by_id($id,
								' AND (id IN(SELECT shoppinglist_id
												FROM shoppinglist_users
												WHERE users_id=:users_id)
										)',
								array('users_id'=>LoggedUser::id())
		);
	}

	public static function update($cols, $id){
		self::_update_by_id($cols,
							$id, 
							' AND (id IN(SELECT shoppinglist_id
											FROM shoppinglist_users
											WHERE users_id=:users_id)
									)',
				array('users_id'=>LoggedUser::id())
		);
	}
	
	public static function add($cols){
		return self::_insert_my($cols);
	}
	
	public static function remove($id){
		self::_remove_my($id);
	}
}