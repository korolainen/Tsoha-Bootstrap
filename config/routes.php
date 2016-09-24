<?php

  	$routes->get('/', function() {
    	FrontController::login();
  	});
  	$routes->get('/forgotpass', function() {
    	FrontController::forgotpass();
  	});
  	$routes->get('/signup', function() {
    	FrontController::register();
  	});
	$routes->get('/logout', function() {
  		FrontController::logout();
  	});


	$routes->get('/search', function() {
		SearchController::search();
	});
	
	
	$routes->get('/profile', function() {
		ProfileController::profile();
	});
  	
  
  	$routes->get('/groups', function() {
  		HelloWorldController::groups();
  	});
	$routes->get('/groups/1', function() {
		HelloWorldController::group();
	});
	
	
	$routes->get('/shops', function() {
  		ShopController::shops();
  	});
	$routes->get('/shops/1', function() {
		ShopController::shop();
	});
	
	
	$routes->get('/shoppinglists', function() {
  		ShoppinglistController::shoppinglists();
  	});
	$routes->get('/shoppinglists/1', function() {
		HelloWorldController::shoppinglist();
	});
	
	
	$routes->get('/products', function() {
  		ProductController::products();
  	});
	$routes->get('/products/1', function() {
		ProductController::product();
	});
	
	
	$routes->get('/license', function() {
  		InfoController::license();
  	});
	
	/*
	if(array_key_exists('no', $_GET)){
		$routes->get('/groups/'.intval($_GET['no']), function() {
			HelloWorldController::group();
		});
		$routes->get('/shoppinglists/'.intval($_GET['no']), function() {
			HelloWorldController::shoppinglist();
		});
		$routes->get('/shops/'.intval($_GET['no']), function() {
			HelloWorldController::shop();
		});
		$routes->get('/products/'.intval($_GET['no']), function() {
			HelloWorldController::product();
		});
	}*/