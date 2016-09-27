<?php
class ShoppinglistProduct extends DataModelCreatedBy implements DataTable{
	public $product_id, $shoppinglist_id, $price, $created_by, $updated, $product, $is_cheapest,
			$name;
			//$price_html;
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
	public static function update($cols, $shoppinglist_id, $product_id){
		$called_class = get_called_class();
		if(!is_callable(array($called_class, self::$base_method))) return;
		$set = Query_Helper::build_bind($cols);
		$statement = 'UPDATE shoppinglist_product
						SET '.$set.', updated=now()
						WHERE product_id=:id AND shoppinglist_id=:shoppinglist_id;';
		$query = DB::connection()->prepare($statement);
		foreach ($cols as $col_key=>$col_val){
			$query->bindParam(':'.$col_key, $col_val);
		}
		$query->bindParam(':id', $shoppinglist_id);
		$query->bindParam(':shoppinglist_id', $product_id);
		$query->execute();
	}


	public static function products_in_shoppinglist($shoppinglist_id){
		$statement = 'SELECT sp.product_id, sp.shoppinglist_id, sp.created_by, sp.price,
							p.id, p.name, p.created_by AS product_created_by, 
							(sp.price IN(SELECT spp.price 
								FROM shoppinglist_product spp
								WHERE spp.product_id = p.id
								ORDER BY spp.price ASC
								LIMIT 1
							)) AS is_cheapest
						FROM '.ShopProduct::get_table_name().' sp
						JOIN '.Product::get_table_name().' p ON p.id = sp.product_id
						WHERE sp.shoppinglist_id=:shoppinglist_id
						ORDER BY p.name ASC;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':shoppinglist_id', $shoppinglist_id);
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			/*
			$row['product'] = new Product(array('id'=>$row['id'],
												'shoppinglist_id'=>$row['shoppinglist_id'],
												'created_by'=>$row['product_created_by']));
			*/
			//$row['price_html'] = CheckData::float_to_currency($row['price']);
			$items[$row['id']] = new ShopProduct($row);
			
		}
		return $items;
	}
	
	public function save(){
		$statement = 'INSERT INTO shoppinglist_product(product_id, shoppinglist_id, price, created_by) 
						VALUES(:product_id, :shoppinglist_id, :price, :created_by);';
		$query = DB::connection()->prepare($statement);
		$query->execute(array('product_id'=>$this->product_id, 
				'shoppinglist_id'=>$this->shoppinglist_id, 
				'price'=>$this->price, 
				'created_by'=>LoggedUser::id()));
	}

	
	public static function remove($shoppinglist_id, $product_id){
		$statement = 'DELETE FROM shoppinglist_product WHERE shoppinglist_id=:shoppinglist_id AND product_id=:product_id;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':shoppinglist_id', $shoppinglist_id);
		$query->bindParam(':product_id', $product_id);
		$query->execute();
	}
}