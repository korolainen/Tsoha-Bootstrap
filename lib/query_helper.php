<?php
class Query_Helper{
	
	private static function build_statement($data = array(), $delimiter = ','){
		$set = '';
		$delimiter_start = '';
		foreach ($data as $col_key=>$col_val){
			$set .= $delimiter_start.$col_key.'=:'.$col_key;
			$delimiter_start = $delimiter;
		}
		return $set;
	}
	
	public static function build_bind($data = array()){
		if(empty($data)) return '';
		return self::build_statement($data);
	}
	
	public static function build_and($data = array()){
		if(empty($data)) return '';
		$statement = self::build_statement($data, ' AND ');
		return '('.$statement.')';
	}
	
	public static function prefix_array_keys($keys, $prefix){
		$prefix_keys = array();
		foreach ($keys as $key){
			$prefix_keys[] = $prefix.'.'.$key;
		}
		return $prefix_keys;
	}
	
	public static function build_insert($table_name, $bind_params, $key = 'id'){
		$insert = 'INSERT INTO '.$table_name.'(';
		$delimiter = '';
		foreach ($bind_params as $col_key=>$col_val){
			$insert .= $delimiter.$col_key;
			$delimiter = ',';
		}
		$insert .= ') VALUES(';
		$delimiter = '';
		foreach ($bind_params as $col_key=>$col_val){
			$insert .= $delimiter.':'.$col_key;
			$delimiter = ',';
		}
		$insert .= ') RETURNING '.$key.';';
		return $insert;
	}
	
	public static function build_items($sql, $class = '', $linking_key = 'id'){
		if(empty($class)) $class = get_called_class();
		if(!class_exists($class, false)) return null;
		$items = array();
		while($row = $sql->fetch(PDO::FETCH_ASSOC)){
			$items[$row[$linking_key]] = new $class($row);
		}
		return $items;
	}
	
	public static function where($bind_params, $where, $statement_prefix = ''){
		$statement = self::build_and($bind_params);
		if(!empty($statement)) $statement .= ' ';
		$statement .= $where;
		if(empty($statement)) return '';
		if(!empty($where)) $statement = '('.$statement.')';
		$start = '';
		if(!empty($statement_prefix)) $start = ' '.$statement_prefix.' ';
		$statement = $start.$statement;
		return $statement;
	}
	
}