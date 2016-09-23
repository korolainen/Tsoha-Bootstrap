<?php
class Query_Helper{

	public static function is_table_name_callable($class){
		//ensimmäinen is_callable on periaatteessa turha, koska kaikki DataModel:ia
		//laajentavat luokat toteuttavat rajapintaluokan DataTable
		//joka sisältää metodin 'get_table_name()'
		if(!is_callable(array($class, 'get_table_name'))) return false;
		$table_name = self::get_table_name();
		if(!is_callable(array($class, $table_name))) return false;
	}
	
	private static function build_statement($data, $delimiter){
		$set = '';
		$delimiter_start = '';
		foreach ($data as $col_key=>$col_val){
			$set = $delimiter.$col_key.'=:'.$col_key;
			$delimiter_start = $delimiter;
		}
		return $set;
	}
	
	public static function build_bind($data){
		return self::build_statement($data, ', ');
	}
	
	public static function build_and($data){
		$statement = self::build_statement($data, ' AND ');
		if(empty($statement)) return '';
		return '('.$statement.')';
	}
	
	public static function prefix_array_keys($keys, $prefix){
		$prefix_keys = array();
		foreach ($keys as $key){
			$prefix_keys[] = $prefix.'.'.$key;
		}
		return $prefix_keys;
	}
	
	public static function build_insert($table_name, $bind_params){
		$insert = 'INSERT INTO '.$table_name.'(';
		$delimiter = '';
		foreach ($bind_params as $col_key=>$col_val){
			$insert .= $delimiter.$col_key;
			$delimiter = ',';
		}
		$insert .= ') VALUES(';
		foreach ($bind_params as $col_key=>$col_val){
			$insert .= $delimiter.':'.$col_key;
			$delimiter = ',';
		}
		$insert .= ');';
		return $insert;
	}
	
	public static function build_items($sql, $class = ''){
		if(empty($class)) $class = get_called_class();
		if(!class_exists($class, false)) return null;
		return $sql->fetchAll(PDO::FETCH_CLASS, $class);
		
		/*
		while($row = $sql->fetch(2)){
			$items[] = new $class($row);
		}
		return $items;
		*/
	}
	
	public static function where($bind_params, $where, $statement_prefix = ''){
		$statement = self::build_and($bind_params);
		if(!empty($statement)) $statement .= ' ';
		$statement .= $where;
		if(empty($statement)) return '';
		if(!empty($where)) $statement = '('.$statement.')';
		$start = '';
		if(!empty($statement_prefix)) $start = ' '.$statement_prefix.' ';
		if($add_where_statement) $statement = $start.$statement;
		return $statement;
	}
	
}