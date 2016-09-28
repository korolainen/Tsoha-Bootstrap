<?php

  class Redirect{

  	public static function direct($path){
  		header('Location: ' . $path);
  		exit();
  	}

    public static function to($path, $message = null){
      if(!is_null($message)){
        $_SESSION['flash_message'] = json_encode($message);
      }
      self::direct(BASE_PATH . $path);
    }
    public static function back($back = null){
    	if(isset($_POST['return'])) self::direct($_POST['return']);
    	if(!is_null($back)) Redirect::to($back);
    	if(isset($_SERVER['HTTP_REFERER'])) {
    		self::direct($_SERVER['HTTP_REFERER']);
    	}
    	Redirect::to('../');
    }
  }
