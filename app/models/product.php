<?php
class Product extends DataModelCreatedBy implements DataTable{
	public $id, $name, $default_unit_id, $created_by,
			$cheapest_shop_id, $cheapest_shop, $shop_ids, $shops,
			$allow_remove;
	public static function get_table_name(){ return 'product'; }
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	
	public static function all(){
		$shops = Shop::all();//ORDER BY pp.name ASC
		$statement = 'SELECT p.id, p.name, p.default_unit_id, p.created_by,
							(SELECT spp.shop_id 
								FROM shop_product spp
								WHERE spp.product_id = p.id
								ORDER BY price ASC
								LIMIT 1
							) cheapest_shop_id,
							(SELECT array_to_string(array_agg(sp.shop_id),\',\')
								FROM shop_product sp
								JOIN product pp ON pp.id = sp.product_id
								WHERE sp.product_id = p.id
								GROUP BY sp.product_id
							) AS shop_ids,
							(created_by=:me) AS allow_remove
				FROM '.self::get_table_name().' p
				WHERE p.created_by=:created_by 
					OR p.id IN(SELECT slp.product_id
								FROM shoppinglist_product slp
								WHERE slp.shoppinglist_id IN(SELECT slu.shoppinglist_id
														FROM shoppinglist_users slu
														WHERE slu.users_id=:shoppinglist_users_id
														)
								) 
				 OR p.id IN(SELECT shp.product_id
								FROM shop_product shp
								WHERE shp.shop_id IN(SELECT su.shop_id
														FROM shop_users su
														WHERE su.users_id=:shop_users_id
														)
								);';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':me', LoggedUser::id());
		$query->bindParam(':created_by', LoggedUser::id());
		$query->bindParam(':shoppinglist_users_id', LoggedUser::id());
		$query->bindParam(':shop_users_id', LoggedUser::id());
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			if(array_key_exists($row['cheapest_shop_id'], $shops)){
				$row['cheapest_shop'] = $shops[$row['cheapest_shop_id']];
			}
			$shop_ids = explode(',', $row['shop_ids']);
			$product_shops = array();
			foreach ($shop_ids as $shop_id){
				if(array_key_exists($shop_id, $shops)){
					$product_shops[] = $shops[$shop_id];
				}
			}
			$row['shops'] = $product_shops;
			$items[$row['id']] = new Product($row);
		}
		return $items;
	}
	
	public static function get($id){
		return self::_get_by_id($id,
				' OR id IN(SELECT product_id
								FROM shoppinglist_product
								WHERE shoppinglist_id IN(SELECT shoppinglist_id
														FROM shoppinglist_users
														WHERE users_id=:shoppinglist_users_id
														)
								) 
				 OR id IN(SELECT product_id
								FROM shop_product
								WHERE shop_id IN(SELECT shop_id
														FROM shop_users
														WHERE users_id=:shop_users_id
														)
								) ',
				array('shoppinglist_users_id'=>LoggedUser::id(),
						'shop_users_id'=>LoggedUser::id()
				)
		);
	}
	
	public static function add($cols){
		return self::_insert_my($cols);
	}
	
	public static function remove($id){
		self::_remove_my($id);
	}
}