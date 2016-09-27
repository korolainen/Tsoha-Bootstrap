<?php
class ShoppinglistProduct extends BaseModel{
	public $product_id, $shoppinglist_id, $created_by, $updated, $product, $is_cheapest,
			$name;
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}	
	public static function all(){
		$statement = 'SELECT product_id,shop_id,price,created_by,updated
				FROM shoppinglist_product
				WHERE shoppinglist_id IN(SELECT shoppinglist_id
								FROM shoppinglist_users
								WHERE users_id=:users_id
								);';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':users_id', LoggedUser::id());
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$items[] = new ShoppinglistProduct($row);
		}
		return $items;
	}
	public static function get($id){
		$statement = 'SELECT product_id,shoppinglist_id,created_by,updated
				FROM shoppinglist_product
				WHERE shoppinglist_id IN(SELECT shoppinglist_id
								FROM shoppinglist_users
								WHERE users_id=:users_id
								) AND id=:id;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':users_id', LoggedUser::id());
		$query->bindParam(':id', $id);
		$query->execute();
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return new ShopProduct($row);
	}


	public static function products_in_shoppinglist($shoppinglist_id){
		$statement = 'SELECT sp.product_id, sp.shoppinglist_id, sp.created_by, 
							p.id, p.name, p.created_by AS product_created_by, 
							(sp.price IN(SELECT spp.price 
								FROM shoppinglist_product spp
								WHERE spp.product_id = p.id
								ORDER BY spp.price ASC
								LIMIT 1
							)) AS is_cheapest
						FROM shoppinglist_product sp
						JOIN product p ON p.id = sp.product_id
						WHERE sp.shoppinglist_id=:shoppinglist_id
						ORDER BY p.name ASC;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':shoppinglist_id', $shoppinglist_id);
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$items[] = new ShopProduct($row);
			
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