<?php

  class BaseModel{
    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null){
      // Käydään assosiaatiolistan avaimet läpi
      foreach($attributes as $attribute => $value){
        // Jos avaimen niminen attribuutti on olemassa...
        if(property_exists($this, $attribute)){
          // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
          $this->{$attribute} = $value;
        }
      }
    }

    public function errors(){
      $errors = array();
      foreach($this->validators as $validator){
		$error = $this->{$validator}();
		$errors = array_merge($errors, $error);
      }
      return $errors;
    }

    public function validate_date($string){
    	$timestamp = CheckData::date_to_ts($string);
    	$errors = array();
    	if(!strtotime($timestamp)){
    		$errors[] = 'Syötä päivämäärä!';
    	}
    	return $errors;
    }

    public function validate_float($string){
        $errors = array();
        if(empty($string)) return $errors;
    	$string = $string."";
        $string = str_replace(" ", "", $string);
        $string = str_replace(",", ".", $string);
        $float = (float)$string;
        $float_string = $float.""; 
        if(strlen($string)!=strlen($float_string)){
        	$errors[] = 'Anna numeerinen arvo!';
        }
        return $errors;        
    }
    
    private function validate_string($string, $length){
    	$errors = array();
    	if($string == null || strlen($string) < 3){
    		$errors[] = '"'.$string.'" on liian lyhyt. 
    					Pituuden tulee olla vähintään '.$length.' merkki'.($length==1 ? '' : 'ä').'!';
    	}
    	return $errors;
    }
    
    public function validate_name(){
    	return $this->validate_string($this->name, 1);
    }
    
    public function validate_price(){
    	return $this->validate_float($this->price);
    }
    
    public function validate_first_name(){
    	return $this->validate_float($this->first_name);
    }
    
    public function validate_active(){
    	return $this->validate_date($this->active);
    }

  }
