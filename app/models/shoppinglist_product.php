<?php
class ShoppinglistProduct extends BaseModel{
	public $product_id, $shoppinglist_id, $created_by, $updated, $product, 
			$cheapest_shop, $cheapest_shop_id, $cheapest_shop_price, $cheapest_shop_price_html,
			$name;
	
	public function __construct($attributes = null){
		parent::__construct($attributes);
		$this->validators = array('product_exists','shoppinglist_exists','shoppinglist_product_not_exists');
		if(isset($attributes['cheapest_shop_price'])){
			$this->cheapest_shop_price_html = CheckData::float_to_currency($this->cheapest_shop_price);
		}
	}
	
	public function product_exists(){
		$errors = array();
		if(intval($this->product_id)>0){
			$product = Product::get($this->product_id);
			if(empty($product)) $errors[] = 'Tuote ei löydy!';
		} 
		return $errors;
	}
	
	public function shoppinglist_exists(){
		$errors = array();
		if(intval($this->shoppinglist_id)>0){
			$shoppinglist = Shoppinglist::get($this->shoppinglist_id);
			if(empty($shoppinglist)) $errors[] = 'Ostoslista ei löydy!';
		} 
		return $errors;
	}
	
	public function shoppinglist_product_not_exists(){
		$errors = array();
		$shoppinglist_product = ShoppinglistProduct::get($this->product_id,$this->shoppinglist_id);
		if(!empty($shoppinglist_product)) $errors[] = 'Ostoslistalla on jo valittu tuote!';
		return $errors;
	}
	
	public static function all(){
		$statement = self::statement();
		$query = DB::connection()->prepare($statement);
		$query->execute(array(':users_id' => LoggedUser::id()));
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$items[] = new ShoppinglistProduct($row);
		}
		return $items;
	}
	
	public static function get($product_id,$shoppinglist_id){
		$statement = self::statement('AND product_id=:product_id AND shoppinglist_id=:shoppinglist_id');
		$query = DB::connection()->prepare($statement);
		$query->execute(array(':users_id' => LoggedUser::id(), 
							':product_id' => $product_id, 
							':shoppinglist_id' => $shoppinglist_id));
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public static function products_in_shoppinglist($shoppinglist_id){
		$shops = Shop::all();
		$shop_products = ShopProduct::all_sorted();
		$statement = 'SELECT sp.product_id, sp.shoppinglist_id, sp.created_by, 
							p.id, p.name, p.created_by AS product_created_by, 
							(SELECT spp.shop_id 
								FROM shop_product spp
								WHERE spp.product_id = p.id
								ORDER BY spp.price ASC
								LIMIT 1
							) AS cheapest_shop_id
						FROM shoppinglist_product sp
						JOIN product p ON p.id = sp.product_id
						WHERE sp.shoppinglist_id=:shoppinglist_id
							AND sp.shoppinglist_id IN(SELECT sss.shoppinglist_id
													FROM shoppinglist_users sss
													WHERE sss.users_id=:users_id)
						ORDER BY p.name ASC;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':shoppinglist_id', $shoppinglist_id);
		$query->bindParam(':users_id', LoggedUser::id());
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			if(isset($shops[$row['cheapest_shop_id']])){
				$row['cheapest_shop'] = $shops[$row['cheapest_shop_id']];
				if(isset($shop_products[$row['cheapest_shop_id']][$row['product_id']])){
					$row['cheapest_shop_price'] = $shop_products[$row['cheapest_shop_id']][$row['product_id']]->price;
				}
			}
			$items[] = new ShoppinglistProduct($row);
			
		}
		return $items;
	}
	
	public function save(){
		$statement = 'INSERT INTO shoppinglist_product(product_id, shoppinglist_id, created_by) 
						VALUES(:product_id, :shoppinglist_id, :created_by);';
		$query = DB::connection()->prepare($statement);
		$query->execute(array('product_id'=>$this->product_id, 
				'shoppinglist_id'=>$this->shoppinglist_id, 
				'created_by'=>LoggedUser::id()));
	}

	
	public static function remove($shoppinglist_id, $product_id){
		$query = DB::connection()->prepare('DELETE FROM shoppinglist_product 
										WHERE shoppinglist_id=:shoppinglist_id 
											AND product_id=:product_id;');
		$query->execute(array(':shoppinglist_id' => $shoppinglist_id, ':product_id' => $product_id));
	}
	
	
	
	
	
	
	

	private static function statement($extra = ''){
		return 'SELECT product_id,shop_id,price,created_by,updated
				FROM shoppinglist_product
				WHERE shoppinglist_id IN(SELECT shoppinglist_id
								FROM shoppinglist_users
								WHERE users_id=:users_id
								)
					'.$extra.';';
	}
}