<?php
class ShopUsergroup extends BaseModel{
	public $usergroup_id, $shop_id, $created_by;
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	
	public static function all(){
		$statement = 'SELECT a.usergroup_id, a.shop_id, a.created_by
									FROM shop_usergroup a
									WHERE (a.created_by=:my_id 
										OR a.usergroup_id IN(SELECT b.usergroup_id 
															FROM shop_users b
															WHERE b.shop_id = a.shop_id
															)
										)
										AND a.usergroup_id=:usergroup_id;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':my_id', LoggedUser::id());
		$query->bindParam(':usergroup_id', $usergroup_id);
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$items[] = new ShopUsergroup($row);
		}
		return $items;
	}
	
	public static function shops($usergroup_id){
		$statement = 'SELECT a.usergroup_id, a.shop_id, a.created_by
									FROM shop_usergroup a
									WHERE (a.created_by=:my_id 
										OR a.usergroup_id IN(SELECT b.usergroup_id 
															FROM shop_users b
															WHERE b.shop_id = a.shop_id
															)
										)
										AND a.usergroup_id=:usergroup_id;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':my_id', LoggedUser::id());
		$query->bindParam(':usergroup_id', $usergroup_id);
		$query->execute();
		$items = array();
		if($row = $query->fetch(PDO::FETCH_ASSOC)){
			return new ShopUsergroup($row);
		}
		return null;
	}
	
	public static function usergroups($shop_id){
		$statement = 'SELECT a.usergroup_id, a.shop_id, a.created_by
									FROM shop_usergroup a
									WHERE (a.created_by=:my_id 
										OR a.usergroup_id IN(SELECT b.usergroup_id 
															FROM shop_users b
															WHERE b.shop_id = a.shop_id
															)
										)
										AND a.shop_id=:shop_id;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':my_id', LoggedUser::id());
		$query->bindParam(':shop_id', $shop_id);
		$query->execute();
		$items = array();
		if($row = $query->fetch(PDO::FETCH_ASSOC)){
			return new ShopUsergroup($row);
		}
		return null;
	}
	
	public static function insert($shop_id, $usergroup_id){
		$statement = 'INSERT INTO shop_usergroup(usergroup_id,shop_id, created_by) 
					VALUES(:usergroup_id,:shop_id,:created_by);';
		$query = DB::connection()->prepare($statement);
		$query->execute(array('usergroup_id'=>$usergroup_id, 'shop_id'=>$shop_id, 'created_by'=>LoggedUser::id()));
	}
	
	public static function remove($shop_id, $usergroup_id){
		$statement = 'DELETE FROM shoppinlist_usergroup WHERE shop_id=:shop_id 
															AND usergroup_id=:usergroup_id
															AND created_by=:created_by;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':shop_id', $shop_id);
		$query->bindParam(':usergroup_id', $usergroup_id);
		$query->bindParam(':created_by', LoggedUser::id());
		$query->execute();
	}
}