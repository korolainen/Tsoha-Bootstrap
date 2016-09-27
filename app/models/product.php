<?php
class Product extends BaseModel{
	public $id, $name, $created_by, 
			$cheapest_shop_id, $cheapest_shop, $cheapest_price, $shop_ids, $shops,
			$allow_remove;
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	
	
	public static function all(){
		$shops = Shop::all();//ORDER BY pp.name ASC
		$statement = 'SELECT p.id, p.name, p.created_by,
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
							(created_by=:me) AS allow_remove
				FROM product p
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
			//$row['name_html'] = CheckData::character_escape($row['name']);
			$items[$row['id']] = new Product($row);
		}
		return $items;
	}


	public function update($id){
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
		$shops = Shop::all();//ORDER BY pp.name ASC
		$statement = 'SELECT p.id, p.name, p.created_by,
							(SELECT spp.shop_id
								FROM shop_product spp
								WHERE spp.product_id = p.id
								ORDER BY spp.price ASC
								LIMIT 1
							) cheapest_shop_id,
							(SELECT array_to_string(array_agg(sp.shop_id),\',\')
								FROM shop_product sp
								JOIN product pp ON pp.id = sp.product_id
								WHERE sp.product_id = p.id
								GROUP BY sp.product_id
							) AS shop_ids,
							(created_by=:me) AS allow_remove
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
								)) AND id=:id;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':me', LoggedUser::id());
		$query->bindParam(':created_by', LoggedUser::id());
		$query->bindParam(':shoppinglist_users_id', LoggedUser::id());
		$query->bindParam(':shop_users_id', LoggedUser::id());
		$query->bindParam(':id', $id);
		$query->execute();
		$items = array();
		if($row = $query->fetch(PDO::FETCH_ASSOC)){
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
			//$row['name_html'] = CheckData::character_escape($row['name']);
			return new Product($row);
		}
		return null;
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
}