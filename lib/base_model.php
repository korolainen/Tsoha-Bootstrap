<?php

  class BaseModel{
    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null){
    	//Mahdollistaa sen että konstruktoreihin voi syöttää suoraan fetch lauseen
      if(is_bool($attributes)) $attributes = array();
      // Käydään assosiaatiolistan avaimet läpi
      foreach($attributes as $attribute => $value){
        // Jos avaimen niminen attribuutti on olemassa...
        if(property_exists($this, $attribute)){
          // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
          $this->{$attribute} = $value;
        }
      }
    }
	/**
	 * Tämä jättää mahdollisuuden sille, että muuttujia voi lisätä vielä ennen lauseen suorittamista
	 * */
	protected static function _query($statement, $params){
		$query = DB::connection()->prepare($statement);
		foreach($params as $k => $param){	
			$query->bindParam(':'.$k, LoggedUser::id());
		}
		return $query;
	}

    public function errors(){
      $errors = array();
      foreach($this->validators as $validator){
		$error = $this->{$validator}();
		$errors = array_merge($errors, $error);
      }
      return $errors;
    }
    
    public function check_errors_and_redirect($url = null){
    	Messages::redirect_errors($this->errors(), $url);	
    }

    public function validate_date($string, $field = ''){
    	$timestamp = CheckData::date_to_ts($string);
    	$errors = array();
    	if(!strtotime($timestamp)){
    		$message_start = '';
    		if(!empty($field)) $message_start = ''.$field.'-kentän muotoa ei tunnistettu. ';
    		$errors[] = $message_start.'Valitse päivämäärä kalenterista!';
    	}
    	return $errors;
    }

    public function validate_float($string, $field = ''){
        $errors = array();
        if(empty($string)) return $errors;
    	$string = $string."";
        $string = str_replace(" ", "", $string);
        $string = str_replace(",", ".", $string);
        $float = (float)$string;
        $float_string = $float.""; 
        if(strlen($string)!=strlen($float_string)){
        	if(!empty($field)) $message_start = ''.$field.'-kentän tieto on virheellinen. ';
        	$errors[] = $message_start.'Anna numeerinen arvo!';
        }
        return $errors;        
    }
    
    public function validate_string($string, $length, $field = ''){
    	$errors = array();
    	if($string == null || strlen($string) < $length){
    		$message_start = '';
    		if(!empty($field)) $message_start = ''.$field.' on liian lyhyt. ';
    		$errors[] = $message_start.'Pituuden tulee olla vähintään '.$length.' merkki'.($length==1 ? '' : 'ä').'!';
    	}
    	return $errors;
    }
    
    public function validation_pattern($condition, $message){
    	$errors = array();
    	if($condition) $errors[] = $message;
    	return $errors;
    }
    /**
     * rajoitus voisi olla myös 1-merkki.
     * 2-merkkiä rajoitteena helpottaa virheilmoitustestausta
     */
    public function validate_name(){
    	return $this->validate_string($this->name, 2, 'Nimi');
    }
    
    public function validate_price(){
    	if(empty($this->price)) return array('Hintatieto on pakollinen!');
    	return $this->validate_float($this->price, 'Hinta');
    }
    
    public function validate_first_name(){
    	return $this->validate_string($this->first_name, 1, 'Etunimi');
    }
    
    public function validate_active(){
    	return $this->validate_date($this->active, 'Päivämäärä');
    }

  }
