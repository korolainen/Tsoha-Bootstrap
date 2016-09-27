<?php

  class BaseController{

  	public static function return_back($path){
  		if(array_key_exists('return', $_POST)){
  			$path = $_POST['return'];
  		}
  		Redirect::to($path);
  	}

  	public static function get_user_logged_in(){
  		return Me::get();
  	}

  	public static function today(){
  		return date('d.m.Y');
  	}

    public static function here(){
      return $_SERVER['REQUEST_URI'];
    }

    public static function check_logged_in(){
	    if(!LoggedUser::is_logged()){
	      	Redirect::to('/');
	    }
    }

  }
