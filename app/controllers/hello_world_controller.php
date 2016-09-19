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
    
  	public static function profile(){
    	View::make('login/profile.html');
    }
    
  	public static function logout(){
    	View::make('login/logout.html');
    }
    
  	public static function stores(){
    	View::make('stores/stores.html');
    }
    
    public static function store(){
    	View::make('stores/store.html');
    }
    
  	public static function products(){
    	View::make('products/products.html');
    }
    
    public static function product(){
    	View::make('products/product.html');
    }
    
    public static function login(){
    	View::make('login/login.html');
    }
    
  	public static function search(){
    	View::make('search/search.html');
    }
    
  	public static function license(){
    	View::make('references/license.html');
    }
    
    
  }
