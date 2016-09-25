<?php
class Shoppinglist extends DataModelCreatedBy implements DataTable{
	public $id, $name, $created_by, $active,
			$is_active,
			$active_date,
			$usergroup,
			$current,
			$allow_remove;
	public static function get_table_name(){ return 'shoppinglist'; }
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	
	public static function all(){
		$usergroups = Usergroup::all();
		$statement = 'SELECT p.id, p.name, p.active, p.created_by,
							(SELECT b.usergroup_id 
								FROM shoppinglist_usergroup b
								WHERE b.shoppinglist_id = p.id
								LIMIT 1
							) AS usergroup_id,
							(p.created_by=:me) AS allow_remove,
							(DATE \'tomorrow\' > NOW()) AS is_active
				FROM '.self::get_table_name().' p
				WHERE p.id IN(SELECT su.shoppinglist_id
								FROM shoppinglist_users su
								WHERE su.users_id=:users_id)
				ORDER BY active DESC;';
		$query = DB::connection()->prepare($statement);
		$query->bindParam(':me', LoggedUser::id());
		$query->bindParam(':users_id', LoggedUser::id());
		$query->execute();
		$items = array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			if(array_key_exists($row['usergroup_id'], $usergroups)){
				$row['usergroup'] = $usergroups[$row['usergroup_id']];
			}
			$row['active_date'] = date('d.m.Y',strtotime($row['active']));
			$row['current'] = $row['is_active']=='1' ? 'current-shoppinglist-row' : 'old-shoppinglist-row'; 
			$items[$row['id']] = new Shoppinglist($row);
		}
		return $items;
	}

	public static function get($id){
		$data = self::_get_by_id($id,
								' AND (id IN(SELECT shoppinglist_id
												FROM shoppinglist_users
												WHERE users_id=:users_id)
										)',
								array('users_id'=>LoggedUser::id())
		);
		if(!empty($data->active)) $data->active_date = date('d.m.Y',strtotime($data->active));
		return $data;
	}

	public static function update($cols, $id){
		self::_update_by_id($cols,
							$id, 
							' AND (id IN(SELECT shoppinglist_id
											FROM shoppinglist_users
											WHERE users_id=:users_id)
									)',
				array('users_id'=>LoggedUser::id())
		);
	}
	
	public static function add($cols){
		return self::_insert_my($cols);
	}
	
	public static function remove($id){
		self::_remove_my($id);
	}
}