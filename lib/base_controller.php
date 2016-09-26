<?php

  class BaseController{
  	


  	public static function return_back($path){
  		if(array_key_exists('return', $_POST)){
  			$path = $_POST['return'];
  		}
  		Redirect::to($path);
  	}

  	public static function visibility(){
  		$visibility = array('add' => 'add-toggle-block',
			  				'add_product' => 'add-toggle-hidden',
			  				'list_products' => 'edit-toggle-block',
  							'edit' => 'edit-toggle-hidden',
  							'editbutton' => true);
  		if(array_key_exists('edit', $_GET)){
  			$visibility = array('add' => 'add-toggle-block',
			  				'add_product' => 'add-toggle-hidden',
			  				'list_products' => 'edit-toggle-hidden',
  							'edit' => 'edit-toggle-block',
  							'editbutton' => true);
  		}else if(array_key_exists('add', $_GET)){
  			$visibility = array('add' => 'add-toggle-hidden',
			  					'add_product' => 'add-toggle-block',
			  					'list_products' => 'edit-toggle-block',
  								'edit' => 'edit-toggle-hidden',
  								'editbutton' => false);
  		}
  		return $visibility;
  	}

  	public static function get_user_logged_in(){
  		self::check_logged_in();
  		return Me::get();
  	}

    public static function here(){
      return $_SERVER['REQUEST_URI'];
    }

    public static function check_logged_in(){
      /*if(!LoggedUser::is_logged()){
      	Redirect::to('/');
      }*/
    }

  }
