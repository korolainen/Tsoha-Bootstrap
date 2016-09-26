<?php
class ShopProduct extends DataModelCreatedBy implements DataTable{
	public $product_id, $shop_id, $price, $created_by, $updated, $product, $is_cheapest,
			$name;
			//$price_html;
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
						SET '.$set.', updated=now()
						WHERE product_id=:id AND shop_id=:shop_id;';
		$query = DB::connection()->prepare($statement);
		foreach ($cols as $col_key=>$col_val){
			$query->bindParam(':'.$col_key, $col_val);
		}
		$query->bindParam(':id', $shop_id);
		$query->bindParam(':shop_id', $product_id);
		$query->execute();
	}


	public static function products_in_shop($shop_id){
		$statement = 'SELECT sp.product_id, sp.shop_id, sp.created_by, sp.price,
							p.id, p.name, p.created_by AS product_created_by, 
							(sp.price IN(SELECT spp.price 
								FROM shop_product spp
								WHERE spp.product_id = p.id
								ORDER BY spp.price ASC
								LIMIT 1
							)) AS is_cheapest
						FROM '.ShopProduct::get_table_name().' sp
						JOIN '.Product::get_table_name().' p ON p.id = sp.product_id
						WHERE sp.shop_id=:shop_id
						ORDER BY p.name ASC;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':shop_id', $shop_id);
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			/*
			$row['product'] = new Product(array('id'=>$row['id'],
												'shop_id'=>$row['shop_id'],
												'created_by'=>$row['product_created_by']));
			*/
			//$row['price_html'] = CheckData::float_to_currency($row['price']);
			$items[$row['id']] = new ShopProduct($row);
			
		}
		return $items;
	}
	
	public function save(){
		$statement = 'INSERT INTO shop_product(product_id, shop_id, price, created_by) 
						VALUES(:product_id, :shop_id, :price, :created_by);';
		$query = DB::connection()->prepare($statement);
		$query->execute(array('product_id'=>$this->product_id, 
				'shop_id'=>$this->shop_id, 
				'price'=>$this->price, 
				'created_by'=>LoggedUser::id()));
	}

	
	public static function remove($shop_id, $product_id){
		$statement = 'DELETE FROM shop_product WHERE shop_id=:shop_id AND product_id=:product_id;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':shop_id', $shop_id);
		$query->bindParam(':product_id', $product_id);
		$query->execute();
	}
}