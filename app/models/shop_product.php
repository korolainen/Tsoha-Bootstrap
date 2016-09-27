<?php
class ShopProduct extends BaseModel{
	public $product_id, $shop_id, $price, $created_by, $updated, $product, $is_cheapest,
			$name;
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}	
	public static function all(){
		$statement = 'SELECT product_id,shop_id,price,created_by,updated
				FROM shop_product
				WHERE shop_id IN(SELECT shop_id
								FROM shop_users
								WHERE users_id=:users_id
								);';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':users_id', LoggedUser::id());
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$items[] = new ShopProduct($row);
		}
		return $items;
	}
	public static function get($id){
		$statement = 'SELECT product_id,shop_id,price,created_by,updated
				FROM shop_product
				WHERE shop_id IN(SELECT shop_id
								FROM shop_users
								WHERE users_id=:users_id
								) AND id=:id;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':users_id', LoggedUser::id());
		$query->bindParam(':id', $id);
		$query->execute();
		if($row = $query->fetch(PDO::FETCH_ASSOC)){
			return new ShopProduct($row);
		}
		return null;
	}
	public function update(){
		$statement = 'UPDATE shop_product
						SET price=:price, updated=now()
						WHERE product_id=:product_id AND shop_id=:shop_id;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':price', $this->price);
		$query->bindParam(':product_id', $this->product_id);
		$query->bindParam(':shop_id', $this->shop_id);
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
						FROM shop_product sp
						JOIN product p ON p.id = sp.product_id
						WHERE sp.shop_id=:shop_id
						ORDER BY p.name ASC;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':shop_id', $shop_id);
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$items[] = new ShopProduct($row);
			
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