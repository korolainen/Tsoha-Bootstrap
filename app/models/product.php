<?php
class Product extends BaseModel{
	public $id, $name, $created_by, 
			$cheapest_shop_id, $cheapest_shop, $cheapest_price, $cheapest_price_html, $shop_ids, $shops,
			$created_by_me, $linkitem;
	public function __construct($attributes = null){
		parent::__construct($attributes);
		if(isset($attributes['cheapest_price'])){
			$this->cheapest_price_html = CheckData::float_to_currency($this->cheapest_price);
		}
		$this->validators = array('validate_name','check_similar');
	}
	public function check_similar(){
		$errors = array();
		$similar = $this->find_exact($this->name);
		if(!empty($similar)){
			$errors[] = 'Samalla nimellä "'.CheckData::character_escape($this->name).'" on jo tuote!';
		} 
		return $errors;
	}
	
	public static function all(){
		return self::execute_select_list(self::build_query(self::list_statement()));
	}
	

	public static function find($name){
		return self::find_exact($name.'%');
	}
	
	
	public static function find_exact($name){
		return self::execute_select_list(self::build_query_name(self::list_statement_name(), $name));
	}
	
	private static function find_not_pattern($statement_difference, $id, $id_key, $name){
		//haetaan täysi vastaavuus
		$query = self::build_query_name(self::list_statement_name($statement_difference, 'TRUE AS linkitem,'), $name);
		$query->bindParam(':'.$id_key, $id);
		$items = self::execute_select_list($query);
		if(empty($items)){
			//haetaan tuotteita jotka alkaa $name
			$query = self::build_query_name(self::list_statement_name($statement_difference, 'TRUE AS linkitem,'), $name.'%');
			$query->bindParam(':'.$id_key, $id);
			$items = self::execute_select_list($query);
			if(empty($items) && strlen($name)>1){
				//haetaan tuotteita jotka sisältää $name
				$query = self::build_query_name(self::list_statement_name($statement_difference, 'FALSE AS linkitem,'), '%'.$name.'%');
				$query->bindParam(':'.$id_key, $id);
				$items = self::execute_select_list($query);
			}
		}
		return $items;
	}
	public static function find_not_in_shop($name, $id){
		$statement_difference = ' AND p.id NOT IN(SELECT sss.product_id 
												FROM shop_product sss
												WHERE sss.shop_id=:shop_id)';
		return self::find_not_pattern($statement_difference, $id, 'shop_id', $name);
	}
	



	public static function find_not_in_shoppinglist($name, $id){
		$statement_difference = 'AND p.id NOT IN(SELECT sss.product_id
												FROM shoppinglist_product sss
												WHERE sss.shoppinglist_id=:shoppinglist_id)';
		return self::find_not_pattern($statement_difference, $id, 'shoppinglist_id', $name);
	}


	public function update(){
		$statement = 'UPDATE product
					SET name=:name
					WHERE id=:id
						AND created_by=:created_by;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':name', $this->name);
		$query->bindParam(':id', $this->id);
		$query->bindParam(':created_by', LoggedUser::id());
		$query->execute();
	}
	
	public static function get($id){
		if(intval($id)<=0) return null;
		$statement = self::list_statement('AND id=:id');
		$query = self::build_query($statement);
		$query->bindParam(':id', $id);
		return self::execute_select_single($query);
	}
	
	public function save(){
		$statement = 'INSERT INTO product(name,created_by) VALUES(:name,:created_by) RETURNING id;';
		$query = DB::connection()->prepare($statement);
		$query->execute(array('name'=>$this->name, 'created_by'=>LoggedUser::id()));
		$row = $query->fetch();
		$this->id = $row['id'];
		return $this->id;
	}
	
	public static function remove($id){
		$statement = 'DELETE FROM product WHERE id=:id AND created_by=:created_by;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':id', $id);
		$query->bindParam(':created_by', LoggedUser::id());
		$query->execute();
	}
	
	
	
	
	
	
	
	
	
	
	
	
	private static function list_statement($extra = '', $extra_param = ''){
		return 'SELECT p.id, p.name, p.created_by,
							(SELECT spp.shop_id
								FROM shop_product spp
								WHERE spp.product_id = p.id
								ORDER BY spp.price ASC
								LIMIT 1
							) cheapest_shop_id,
							(SELECT spp.price
								FROM shop_product spp
								WHERE spp.product_id = p.id
								ORDER BY spp.price ASC
								LIMIT 1
							) cheapest_price,
							(SELECT array_to_string(array_agg(sp.shop_id),\',\')
								FROM shop_product sp
								JOIN product pp ON pp.id = sp.product_id
								WHERE sp.product_id = p.id
								GROUP BY sp.product_id
							) AS shop_ids,
							'.$extra_param.'
							(p.created_by=:me) AS created_by_me
				FROM product p
				WHERE (p.created_by=:created_by
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
								)) '.$extra.'
				ORDER BY p.name ASC;';
	}
	private static function list_statement_name($extra = '', $extra_param = ''){
		return self::list_statement(' AND LOWER(p.name) LIKE :name '.$extra, $extra_param);
	}

	private static function build_query($statement){
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':me', LoggedUser::id());
		$query->bindParam(':created_by', LoggedUser::id());
		$query->bindParam(':shoppinglist_users_id', LoggedUser::id());
		$query->bindParam(':shop_users_id', LoggedUser::id());
		return $query;
	}
	private static function build_query_name($statement, $name){
		$query = self::build_query($statement);
		$name = strtolower($name);
		$query->bindParam(':name', $name);
		return $query;
	}
	
	private static function build_item($row, $shops = array()){
		if(isset($shops[$row['cheapest_shop_id']])){
			$row['cheapest_shop'] = $shops[$row['cheapest_shop_id']];
		}
		$shop_ids = explode(',', $row['shop_ids']);
		$product_shops = array();
		foreach ($shop_ids as $shop_id){
			if(isset($shops[$shop_id])){
				$product_shops[] = $shops[$shop_id];
			}
		}
		$row['shops'] = $product_shops;
		//$row['name_html'] = CheckData::character_escape($row['name']);
		return new Product($row);
	}
	
	private static function execute_select_single($query){
		$shops = Shop::all();
		$query->execute();
		$items = array();
		if($row = $query->fetch(PDO::FETCH_ASSOC)){
			return self::build_item($row, $shops);
		}
		return null;
	}
	
	private static function execute_select_list($query){
		$shops = Shop::all();
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$items[$row['id']] = self::build_item($row, $shops);
		}
		return $items;
	}
}