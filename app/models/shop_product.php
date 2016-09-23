<?php
class ShopProduct extends DataModelCreatedBy implements DataTable{
	public $product_id, $shop_id, $price, $quantity, $unit_id, $created_by, $updated;
	public static function get_table_name(){ return 'shop_product'; }
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}	
	public static function all(){
		return self::_get(null, 
					' shop_id IN(SELECT shop_id
								FROM shop_users
								WHERE users_id=:users_id
								) ',
					array('users_id'=>LoggedUser::id()));
	}
	public static function get($id){
		return self::_get_by_id($id,
				' AND shop_id IN(SELECT shop_id
								FROM shop_users
								WHERE users_id=:users_id
								)',
				array('users_id'=>LoggedUser::id()
				)
		);
	}
	public static function update($cols, $id){
		self::_update_by_id($cols, 
							$id, 
							' AND shop_id IN(SELECT shop_id
											FROM shop_users
											WHERE users_id=:users_id
											)',
							array('users_id' => LoggedUser::id())
		);
	}
	/*	
	public static function all(){
		return self::_get(array('created_by'=>LoggedUser::id()), 
					' OR id IN(SELECT shop_id
								FROM '.ShopUsergroup::get_table_name().'
								WHERE usergroup_id IN(SELECT id
													FROM '.Usergroup::get_table_name().'
													WHERE users_id=:users_id
												)
									) ',
					array('users_id'=>LoggedUser::id()));
	}

	public static function get($id){
		return self::_get_by_id($id,
				' AND (created_by=:created_by
							OR id IN(SELECT shop_id
									FROM '.ShopUsergroup::get_table_name().'
									WHERE usergroup_id IN(SELECT id
														FROM '.Usergroup::get_table_name().'
														WHERE users_id=:users_id
													)
										)
							)',
				array('created_by'=>LoggedUser::id(),
						'users_id'=>LoggedUser::id()
				)
		);
	}
	public static function update($cols, $id){
		self::_update_by_id($cols, 
							$id, 
							' AND (id IN(SELECT shop_id 
										FROM '.ShopUsergroup::get_table_name().'
										WHERE usergroup_id IN(SELECT id
																FROM '.Usergroup::get_table_name().'
																WHERE users_id=:users_id
															)
										)
									OR created_by=:my_id
									)',
							array('created_by' => LoggedUser::id())
		);
	}
	*/
	public static function add($cols){
		return self::_insert_my($cols);
	}
	public static function remove($id){
		self::_remove_my($id);
	}
}