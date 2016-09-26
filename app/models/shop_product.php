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
	public static function update($cols, $shop_id, $product_id){
		$called_class = get_called_class();
		if(!is_callable(array($called_class, self::$base_method))) return;
		$set = Query_Helper::build_bind($cols);
		$statement = 'UPDATE shop_product
						SET '.$set.'
						WHERE product_id=:id AND shop_id=:shop_id;';
		$query = DB::connection()->prepare($statement);
		$query->execute(array_merge($set, array('shop_id'=>$shop_id, 'product_id'=>$product_id)));
		/*
		self::_update($cols, 'product_id', $product_id, 
							' AND shop_id IN(SELECT shop_id
											FROM shop_users
											WHERE users_id=:users_id
											)
								AND shop_id=:shop_id',
							array('users_id' => LoggedUser::id(), 'shop_id' => $shop_id)
		);*/
	}


	public static function products_in_shop($shop_id){
		$statement = 'SELECT p.id, p.name, p.default_unit_id, p.created_by, sp.price
						FROM '.ShopProduct::get_table_name().' sp
						JOIN '.Product::get_table_name().' p ON p.id = sp.product_id
						WHERE sp.shop_id=:shop_id
						ORDER BY p.name ASC;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':shop_id', $shop_id);
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$row = Product::build_html_fields($row);
			$items[$row['id']] = new Product($row);
		}
		return $items;
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