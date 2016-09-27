<?php
class ShoppinglistUsergroup extends BaseModel{
	public $shoppinglist_id, $usergroup_id, $created_by;
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	
	public static function all($usergroup_id){
		$statement = 'SELECT p.id, p.name, p.created_by,
							(SELECT b.usergroup_id
								FROM shop_usergroup b
								WHERE b.shop_id = p.id
								LIMIT 1
							) AS usergroup_id,
							(p.created_by=:me) AS allow_remove
				FROM shop p
				WHERE p.id IN(SELECT su.shop_id
								FROM shop_users su
								WHERE su.users_id=:users_id);';
		$query = DB::connection()->prepare($statement);
		$query->bindParam('my_id', LoggedUser::id());
		$query->bindParam('my_id_c', LoggedUser::id());
		$query->bindParam('my_id_d', LoggedUser::id());
		$query->bindParam('usergroup_id', $usergroup_id);
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$items[$row['id']] = new Shoppinglist($row);
		}
		return $items;
	}
	
	public static function insert($shoppinglist_id, $usergroup_id){
		$statement = 'INSERT INTO shoppinglist_usergroup(usergroup_id,shoppinglist_id,created_by) 
					VALUES(:usergroup_id,:shoppinglist_id,:created_by);';
		$query = DB::connection()->prepare($statement);
		$query->execute(array('usergroup_id'=>$usergroup_id, 'shoppinglist_id'=>$shoppinglist_id, 'created_by'=>LoggedUser::id()));
	}
	
	public static function remove($shoppinglist_id, $usergroup_id){
		$statement = 'DELETE FROM shoppinglist_usergroup WHERE shoppinglist_id=:shoppinglist_id 
															AND usergroup_id=:usergroup_id
															AND created_by=:created_by;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':shoppinglist_id', $shoppinglist_id);
		$query->bindParam(':usergroup_id', $usergroup_id);
		$query->bindParam(':created_by', LoggedUser::id());
		$query->execute();
	}
}