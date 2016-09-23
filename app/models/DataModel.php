<?php
class DataModel extends BaseModel{
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	
	
	
	
	/* SELECT */
	
	protected static function _get($bind_params = array(), $where = '', $where_bind_params = array()){
		$where = Query_Helper::where($bind_params, $where, 'WHERE');
		$items = array();
		$called_class = get_called_class();
		if(!Query_Helper::is_table_name_callable($called_class)) return null;
		$query = DB::connection()->prepare('SELECT * FROM '.$called_class::get_table_name().' '.$where.';');
		foreach ($bind_params as $col_key=>$col_val){
			$query->bindParam(':'.$col_key, $col_val);
		}
		foreach ($where_bind_params as $col_key=>$col_val){
			$query->bindParam(':'.$col_key, $col_val);
		}
		$sql = $query->execute();
		return Query_Helper::build_items($sql, $called_class);
	}
	
	protected static function _get_by_id($id, $where = '', $where_bind_params = array()){
		return self::_get(array('id'=>$id), $where, $where_bind_params);
	}
	
	protected static function _all(){
		return self::_get();
	}
	
	protected static function _select_execute($select, $bind_params = array()){
		$called_class = get_called_class();
		if(!Query_Helper::is_table_name_callable($called_class)) return null;
		$query = DB::connection()->prepare($select);
		foreach ($bind_params as $col_key=>$col_val){
			$query->bindParam(':'.$col_key, $col_val);
		}
		return $query->execute();
	}
	
	
	
	
	
	
	/* INSERT */

	protected static function _insert($bind_params){
		$called_class = get_called_class();
		if(!Query_Helper::is_table_name_callable($called_class)) return;
		$insert = Query_Helper::build_insert($called_class::get_table_name(), $bind_params);
		$query = DB::connection()->prepare($insert);
		foreach ($bind_params as $col_key=>$col_val){
			$query->bindParam(':'.$col_key, $col_val);
		}
		$query->execute();
	}
	
	
	
	
	
	
	
	
	
	
	/* UPDATE */

	protected static function _update($data, $key, $id, $where = '', $where_bind_params = array()){
		$called_class = get_called_class();
		if(!Query_Helper::is_table_name_callable($called_class)) return;
		$set = Query_Helper::build_bind($data);
		$query = DB::connection()->prepare('UPDATE '.$called_class::get_table_name().'
											SET '.$set.'
											WHERE '.$key.' = :id '.$where.'
											LIMIT 1;');
		foreach ($data as $col_key=>$col_val){
			$query->bindParam(':'.$col_key, $col_val);
		}
		$query->bindParam(':id', $id);
		foreach ($where_bind_params as $col_key=>$col_val){
			$query->bindParam(':'.$col_key, $col_val);
		}
		$query->execute();
	}
	
	protected static function _update_by_id($data, $id, $where = '', $where_bind_params = array()){
		self::_update($data, 'id', $id, $where, $where_bind_params);
	}
	
	
	
	
	
	
	
	
	/* DELETE */
	
	protected static function _remove($key, $id, $bind_params = array(), $where = '', $where_bind_params = array()){
		$where = Query_Helper::where($bind_params, $where, 'AND');
		$called_class = get_called_class();
		if(!Query_Helper::is_table_name_callable($called_class)) return;
		$query = DB::connection()->prepare('DELETE FROM '.$called_class::get_table_name().' 
											WHERE '.$key.' = :id '.$where.' 
											LIMIT 1;');
		$query->bindParam(':id', $id);
		foreach ($bind_params as $col_key=>$col_val){
			$query->bindParam(':'.$col_key, $col_val);
		}
		foreach ($where_bind_params as $col_key=>$col_val){
			$query->bindParam(':'.$col_key, $col_val);
		}
		$query->execute();
	}
	
	protected static function _remove_by_id($key, $bind_params = array(), $where = '', $where_bind_params = array()){
		self::_remove($key, 'id', $where, $where_bind_params);
	}
	
}
class DataModelCreatedBy extends DataModel{
	
	protected static function _insert_my($cols){
		$cols['created_by'] = LoggedUser::id();
		return self::_insert($cols);
	}
	
	protected static function _remove_my($id){
		self::_remove_by_id($id, array('created_by' => LoggedUser::id()));
	}
}
/*
class DataModelCreatedBy extends DataModel{
	public function __construct($attributes){
		parent::__construct($attributes);
	}
	
	protected static function _get($bind_params = array(), $where = '', $where_bind_params = array()){
		if(!empty($where)) $where = ' AND ('.$where.') ';
		if(!LoggedUser::is_logged()) return null;
		return parent::_get('created_by=:created_by '.$where, 
							array_merge(array('created_by' => LoggedUser::id()), $bind_params), 
							$where_bind_params);
	}
	protected static function _remove($key, $id, $bind_params = array(), $where = ''){
		if(!empty($where)) $where = ' AND ('.$where.') ';
		parent::_remove($key, 
						$id, 
						'created_by=:created_by '.$where, 
						array_merge(array('created_by' => LoggedUser::id()), $bind_params));
	}
	protected static function _update($data, $key, $id, $bind_params = array(), $where = '', $where_bind_params = array()){
		if(!empty($where)) $where = ' AND ('.$where.') ';
		parent::_update($data, 
						$key, 
						$id, 'created_by=:created_by '.$where, 
						array_merge(array('created_by' => LoggedUser::id()), $bind_params), 
						$where_bind_params);
	}
	
}
*/