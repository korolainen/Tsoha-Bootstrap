<?php
class Unit extends DataModel implements DataTable{
	public $id, $name;
	public static function get_table_name(){ return 'unit'; }
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	public static function all(){
		return self::_all();
	} 
}