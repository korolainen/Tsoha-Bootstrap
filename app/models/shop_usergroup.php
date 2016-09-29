<?php
class ShopUsergroup extends BaseModel{
	public $usergroup_id, $shop_id, $created_by;
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	/*
	public static function all(){
		return self::execute(self::query(self::statement()));
	}
	
	public static function shops($usergroup_id){
		$query = self::query(self::statement('AND a.usergroup_id=:usergroup_id'));
		$query->bindParam(':usergroup_id', $usergroup_id);
		return self::execute($query);
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
	*/
	public static function insert($shop_id, $usergroup_id){
		$query = DB::connection()->prepare('INSERT INTO shop_usergroup(usergroup_id,shop_id, created_by) 
					VALUES(:usergroup_id,:shop_id,:created_by);');
		$query->execute(array('usergroup_id'=>$usergroup_id, 'shop_id'=>$shop_id, 'created_by'=>LoggedUser::id()));
	}
	
	public static function remove($shop_id, $usergroup_id){
		$query = DB::connection()->prepare('DELETE FROM shop_usergroup 
											WHERE shop_id=:shop_id 
												AND usergroup_id=:usergroup_id
												AND created_by=:created_by;');
		$query->execute(array(':shop_id'=>$shop_id, ':usergroup_id'=>$usergroup_id, ':created_by'=>LoggedUser::id()));
	}
	
	
	
	
	/*
	


	private static function statement($extra = ''){
		return 'SELECT a.usergroup_id, a.shop_id, a.created_by
									FROM shop_usergroup a
									WHERE (a.created_by=:my_id
										OR a.usergroup_id IN(SELECT b.usergroup_id
															FROM shop_users b
															WHERE b.shop_id = a.shop_id
															)
										) '.$extra.';';
	}
	
	private static function query($statement){
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':my_id', LoggedUser::id());
	}
	
	private static function execute($query){
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$items[] = new ShopUsergroup($row);
		}
		return $items;
	}
	*/
}