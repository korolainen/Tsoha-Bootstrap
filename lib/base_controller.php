<?php

  class BaseController{

  	public static function get_user_logged_in(){
  		return Me::get();
  	}

    public static function check_logged_in(){
	    if(!LoggedUser::is_logged()){
	      	Redirect::to('/');
	    }
    }

  }
