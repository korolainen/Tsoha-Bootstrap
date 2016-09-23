<?php
class Product extends DataModelCreatedBy implements DataTable{
	public $id, $name, $default_unit_id, $created_by;
	public static function get_table_name(){ return 'product'; }
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	
	public static function add($cols){
		return self::_insert_my($cols);
	}
	
	public static function remove($id){
		self::_remove_my($id);
	}
}