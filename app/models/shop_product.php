<?php
class ShopProduct extends BaseModel{
	public $product_id, $shop_id, $price, $price_html, $created_by, $updated, $product, $is_cheapest,
			$name, $shop_name;
	public function __construct($attributes = null){
		parent::__construct($attributes);
		$this->validators = array('validate_price','product_exists','shop_exists','shop_product_not_exists');
		if(isset($attributes['price'])){
			$this->price_html = CheckData::float_to_currency($this->price);
		}
	}
		
	public function check_price(){
		$errors = $this->validate_price();
		if(!empty($errors)){
			echo 'error';
			exit();
		}
	}
	
	public function product_exists(){
		$product = Product::get(intval($this->product_id));
		return $this->validation_pattern(empty($product), 'Tuote ei löydy!');
	}
	
	public function shop_exists(){
		$shop = Shop::get(intval($this->shop_id));
		return $this->validation_pattern(empty($shop), 'Kauppa ei löydy!');
	}
	
	public function shop_product_not_exists(){
		$shop_product = ShopProduct::get($this->product_id,$this->shop_id);
		return $this->validation_pattern((!empty($shop_product)), 'Kaupassa on jo valittu tuote!');
	}
	
	public static function all(){
		return self::execute(self::execute_all());
	}
	
	public static function all_sorted(){
		$query = self::execute_all();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$items[$row['shop_id']][$row['product_id']] = new ShopProduct($row);
		}
		return $items;
	}
	
	public function update(){
		$query = DB::connection()->prepare('UPDATE shop_product
											SET price=:price, updated=now()
											WHERE product_id=:product_id AND shop_id=:shop_id;');
		$query->execute(array(':price'=>$this->price, ':product_id'=>$this->product_id, ':shop_id'=>$this->shop_id));
	}

	public static function products_in_shop($shop_id){
		$statement=self::statement('AND sp.shop_id=:shop_id');
		$query = self::query($statement);
		$query->bindParam(':shop_id', $shop_id);
		return self::execute($query);
	}


	public static function get($shop_id, $product_id){
		$statement=self::statement('AND p.id=:product_id AND sp.shop_id=:shop_id');
		$query = self::query($statement);
		$query->bindParam(':shop_id', $shop_id);
		$query->bindParam(':product_id', $product_id);
		$items = self::execute($query);
		if(count($items)<=0) return null;
		return current($items);

	}

	public static function product_in_shops($product_id){
		$statement=self::statement('AND p.id=:product_id');
		$query = self::query($statement);
		$query->bindParam(':product_id', $product_id);
		return self::execute($query);
	}

	public static function product_not_in_shops($product_id){
		$statement = 'SELECT s.name, s.id AS shop_id
						FROM shop s
						WHERE s.id NOT IN (SELECT pp.shop_id FROM shop_product pp WHERE product_id=:product_id)
							AND (s.id IN (SELECT ppp.shop_id 
											FROM shop_users ppp
											WHERE ppp.users_id=:users_id
											)
								)
						ORDER BY s.name ASC;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':product_id', $product_id);
		$query->bindParam(':users_id', LoggedUser::id());
		return self::execute($query);
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
		$query = DB::connection()->prepare('DELETE FROM shop_product 
											WHERE shop_id=:shop_id 
												AND product_id=:product_id;');
		$query->execute(array('shop_id'=>$shop_id, 'product_id'=>$product_id));
	}
	
	
	
	
	
	
	


	private static function execute_all(){
		$statement = 'SELECT product_id,shop_id,price,created_by,updated
				FROM shop_product
				WHERE shop_id IN(SELECT shop_id
								FROM shop_users
								WHERE users_id=:users_id
								);';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':users_id', LoggedUser::id());
		$query->execute();
		return $query;
	}
	
	private static function statement($extra = ''){
		return 'SELECT sp.product_id, sp.shop_id, sp.created_by, sp.price,
							p.id, p.name, p.created_by AS product_created_by,
							(sp.price IN(SELECT spp.price
								FROM shop_product spp
								WHERE spp.product_id = p.id
									AND spp.shop_id IN(SELECT pppp.shop_id
											FROM shop_users pppp
											WHERE pppp.users_id=:shop_users_id)
								ORDER BY spp.price ASC
								LIMIT 1
							)) AS is_cheapest,
							s.name AS shop_name
						FROM shop_product sp
						JOIN product p ON p.id = sp.product_id
						JOIN shop s ON s.id = sp.shop_id
						WHERE sp.shop_id IN(SELECT ppp.shop_id
											FROM shop_users ppp
											WHERE ppp.users_id=:users_id)
						'.$extra.'
						ORDER BY p.name ASC, s.name ASC;';
	}
	
	private static function query($statement){
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':shop_users_id', LoggedUser::id());
		$query->bindParam(':users_id', LoggedUser::id());
		return $query;
	}
	
	private static function execute($query){
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$items[] = new ShopProduct($row);
		}
		return $items;
	}
}