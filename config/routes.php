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
	$routes->get('/groups/group/:id', function($id) {
		UsergroupController::group($id);
	});
	$routes->get('/groups/group/:id/remove', function($id) {
		UsergroupController::remove($id);
	});
	$routes->post('/groups/group/:id/edit', function($id) {
		UsergroupController::edit($id);
	});
	$routes->get('/groups/new', function() {
		UsergroupController::create_new();
	});
	
	
	$routes->get('/shops', function() {
  		ShopController::shops();
  	});
	$routes->get('/shops/shop/:id', function($id) {
		ShopController::shop($id);
	});
	$routes->get('/shops/shop/:id/remove', function($id) {
		ShopController::remove($id);
	});
	$routes->post('/shops/shop/:id/edit', function($id) {
		ShopController::edit($id);
	});
	$routes->get('/shops/new', function() {
		ShopController::create_new();
	});
	
	
	$routes->get('/shoppinglists', function() {
  		ShoppinglistController::shoppinglists();
  	});
	$routes->get('/shoppinglists/shoppinglist/:id', function($id) {
		ShoppinglistController::shoppinglist($id);
	});
	$routes->get('/shoppinglists/shoppinglist/:id/remove', function($id) {
		ShoppinglistController::remove($id);
	});
	$routes->post('/shoppinglists/shoppinglist/:id/edit', function($id) {
		ShoppinglistController::edit($id);
	});
	$routes->get('/shoppinglists/new', function() {
		ShoppinglistController::create_new();
	});
	

	$routes->get('/shopproducts/shopproduct/:shop_id/:product_id/edit', function($shop_id, $product_id) {
		ShopProductController::edit($shop_id, $product_id);
	});
	$routes->post('/shopproducts/shopproduct/:shop_id/:product_id/edit', function($shop_id, $product_id) {
		ShopProductController::edit($shop_id, $product_id);
	});
	
	
	$routes->get('/products', function() {
  		ProductController::products();
  	});
	$routes->get('/products/product/:id/edit', function($id) {
		ProductController::edit($id);
	});
	$routes->get('/products/product/:id/remove', function($id) {
		ProductController::remove($id);
	});
	$routes->get('/products/product/:id', function($id) {
		ProductController::product($id);
	});
	$routes->get('/products/new', function() {
		ProductController::create_new();
	});
	
	
	$routes->get('/license', function() {
  		InfoController::license();
  	});