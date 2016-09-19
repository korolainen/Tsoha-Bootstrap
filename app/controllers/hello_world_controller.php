<?php

  class HelloWorldController extends BaseController{

  	public static function index(){
  		View::make('login/login.html');
  	}
    public static function forgotpass(){
   	  View::make('login/forgotpass.html');
    }
    public static function register(){
   	  View::make('login/signup.html');
    }
    
  public static function groups(){
    	View::make('groups/groups.html');
    }
    
    public static function group(){
    	View::make('groups/group.html');
    }
    
  	public static function shoppinglists(){
    	View::make('shoppinglists/shoppinglists.html');
    }
    
    public static function shoppinglist(){
    	View::make('shoppinglists/shoppinglist.html');
    }
    
  	public static function shops(){
    	View::make('shops/shops.html');
    }
    
    public static function shop(){
    	View::make('shops/shop.html');
    }
    
    public static function login(){
    	View::make('login/login.html');
    }
    
    
  }
