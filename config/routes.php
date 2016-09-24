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
  		UsergroupController::groups();
  	});
	$routes->get('/groups/group', function() {
		UsergroupController::group();
	});
	
	
	$routes->get('/shops', function() {
  		ShopController::shops();
  	});
	$routes->get('/shops/shop', function() {
		ShopController::shop();
	});
	
	
	$routes->get('/shoppinglists', function() {
  		ShoppinglistController::shoppinglists();
  	});
	$routes->get('/shoppinglists/shoppinglist', function() {
		ShoppinglistController::shoppinglist();
	});
	
	
	$routes->get('/products', function() {
  		ProductController::products();
  	});
	$routes->get('/products/product', function() {
		ProductController::product();
	});
	
	
	$routes->get('/license', function() {
  		InfoController::license();
  	});