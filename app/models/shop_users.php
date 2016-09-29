<?php
class ShopUsers{

	public static function users($shop_id){
		$query = DB::connection()->prepare('SELECT u.id, u.account, u.first_name, u.last_name, u.phone, u.hash
						FROM users u
						JOIN shop_users su ON su.users_id=u.id AND su.shop_id=:shop_id
				WHERE su.users_id!=:me;');
		$query->execute(array('shop_id'=>$shop_id,'me'=>LoggedUser::id()));
		$item = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$item[$row['id']] = new User($row);
		}
		return $item;
	}
	
}