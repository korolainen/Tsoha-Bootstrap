<?php
class ShoppinglistProduct extends DataModelCreatedBy implements DataTable{
	public $shoppinglist_id, $product_id, $description, $unit_id, $quantity, $created_by, $created;
	public static function get_table_name(){ return 'shoppinglist_product'; }
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	public static function all(){
		return self::_get(null,
				' shoppinglist_id IN(SELECT shoppinglist_id
								FROM shoppinglist_users
								WHERE users_id=:users_id
								) ',
				array('users_id'=>LoggedUser::id()));
	}
	public static function get($id){
		return self::_get_by_id($id,
				' AND shoppinglist_id IN(SELECT shoppinglist_id
								FROM shoppinglist_users
								WHERE users_id=:users_id
								)',
				array('users_id'=>LoggedUser::id()
				)
		);
	}
	public static function update($cols, $id){
		self::_update_by_id($cols,
				$id,
				' AND shoppinglist_id IN(SELECT shoppinglist_id
											FROM shoppinglist_users
											WHERE users_id=:users_id
											)',
				array('users_id' => LoggedUser::id())
		);
	}
	public static function add($cols){
		return self::_insert_my($cols);
	}
	public static function remove($id){
		self::_remove_my($id);
	}
}