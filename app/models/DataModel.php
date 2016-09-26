<?php
class DataModel extends BaseModel{
	protected static $base_method = 'get_table_name';
	public function __construct($attributes = null){
		parent::__construct($attributes);
	}
	
	
	
	
	/* SELECT */
	
	protected static function _get($bind_params = array(), $where = '', $where_bind_params = array(), $limit = ''){
		$where = Query_Helper::where($bind_params, $where, 'WHERE');
		$items = array();
		$called_class = get_called_class();
		if(!is_callable(array($called_class, self::$base_method))) return null;
		$statement = 'SELECT * FROM '.call_user_func(array($called_class, self::$base_method)).' '.$where.' '.$limit.';';
		$query = DB::connection()->prepare($statement);
		if(!empty($bind_params)){
			foreach ($bind_params as $col_key=>$col_val){
				$query->bindParam(':'.$col_key, $col_val);
			}
		}
		if(!empty($where_bind_params)){
			foreach ($where_bind_params as $col_key=>$col_val){
				$query->bindParam(':'.$col_key, $col_val);
			}
		}
		$query->execute();
		return Query_Helper::build_items($query, $called_class);
	}
	
	protected static function _get_by_id($id, $where = '', $where_bind_params = array()){
		$items = self::_get(array('id'=>$id), $where, $where_bind_params, 'LIMIT 1');
		if(empty($items)) return null;
		return current($items);
	}
	
	protected static function _all(){
		return self::_get();
	}
	
	protected static function _select_execute($select, $bind_params = array()){
		$called_class = get_called_class();
		if(!is_callable(array($called_class, self::$base_method))) return null;
		$query = DB::connection()->prepare($select);
		if(!empty($bind_params)){
			foreach ($bind_params as $col_key=>$col_val){
				$query->bindParam(':'.$col_key, $col_val);
			}
		}
		return $query->execute();
	}
	
	
	
	
	
	
	/* INSERT */

	protected static function _insert($bind_params){
		$called_class = get_called_class();
		if(!is_callable(array($called_class, self::$base_method))) return;
		$insert = Query_Helper::build_insert($called_class::get_table_name(), $bind_params);
		$query = DB::connection()->prepare($insert);
		if(!empty($bind_params)){
			foreach ($bind_params as $col_key=>$col_val){
				$query->bindParam(':'.$col_key, $col_val);
			}
		}
		$query->execute();
	}
	
	
	
	
	
	
	
	
	
	
	/* UPDATE */

	protected static function _update($data, $key, $id, $where = '', $where_bind_params = array()){
		$called_class = get_called_class();
		if(!is_callable(array($called_class, self::$base_method))) return;
		$set = Query_Helper::build_bind($data);
		$query = DB::connection()->prepare('UPDATE '.$called_class::get_table_name().'
											SET '.$set.'
											WHERE '.$key.' = :id '.$where.'
											LIMIT 1;');
		if(!empty($data)){
			foreach ($data as $col_key=>$col_val){
				var_dump($col_key);
				$query->bindParam(':'.$col_key, $col_val);
			}
		}
		$query->bindParam(':id', $id);
		if(!empty($where_bind_params)){
			foreach ($where_bind_params as $col_key=>$col_val){
				var_dump($col_key);
				$query->bindParam(':'.$col_key, $col_val);
			}
		}
		exit();
		$query->execute();
	}
	
	protected static function _update_by_id($data, $id, $where = '', $where_bind_params = array()){
		self::_update($data, 'id', $id, $where, $where_bind_params);
	}
	
	
	
	
	
	
	
	
	/* DELETE */
	
	protected static function _remove($key, $id, $bind_params = array(), $where = '', $where_bind_params = array()){
		$where = Query_Helper::where($bind_params, $where, 'AND');
		$called_class = get_called_class();
		if(!is_callable(array($called_class, self::$base_method))) return;
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