<?php
class Usergroup extends DataModelCreatedBy implements DataTable{
	public $id, $name, $created_by;
	public static function get_table_name(){ return 'usergroup'; }
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	
	public static function all(){
		//näytetään ryhmät johon käyttäjä kuuluu ja ryhmät jotka käyttäjä on luonut
		return self::_get(array('created_by'=>LoggedUser::id()), 
					' OR id IN(SELECT usergroup_id 
							FROM '.UsergroupUsers::get_table_name().' 
							WHERE users_id=:users_id)',
					array('users_id'=>LoggedUser::id()));
	}
	
	public static function get($id){
		//näytetään ryhmä johon käyttäjä kuuluu tai ryhmä jotka käyttäjä on luonut
		return self::_get_by_id($id, 
					' AND (created_by=:created_by 
							OR id IN(SELECT usergroup_id 
									FROM '.UsergroupUsers::get_table_name().' 
									WHERE users_id=:users_id)
							)',
					array('created_by'=>LoggedUser::id(), 
							'users_id'=>LoggedUser::id()
					)
		);
	}
	
	public static function update($cols, $id){
		//sallitaan päivitys vain jos ryhmä on tehty itse tai on ryhmän jäsen
		self::_update_by_id($cols, 
							$id, 
							' AND (id IN(SELECT usergroup_id 
										FROM '.UsergroupUsers::get_table_name().'
										WHERE users_id=:users_id
										)
									OR created_by=:my_id
									)',
							array('created_by' => LoggedUser::id())
		);
	}
	
	public static function add($cols){
		return self::_insert_my($cols);
	}
	
	public static function remove($id){
		self::_remove_my($id);
	}
	
}