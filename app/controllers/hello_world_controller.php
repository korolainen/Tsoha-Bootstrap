<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  View::make('home.html');
    }

    public static function sandbox(){
      // Testaa koodiasi täällä
      echo 'Hello World!';
    }
    
    
  public static function groups(){
    	View::make('groups/groups.html');
    }
    
    public static function group(){
    	View::make('groups/groups.html');
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
