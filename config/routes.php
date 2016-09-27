<?php

	$routes->get('/', function() {
		FrontController::login_page();
	});
  	$routes->post('/login', function() {
    	ProfileController::login();
  	});
  	$routes->get('/forgotpass', function() {
    	FrontController::forgotpass();
  	});
  	$routes->get('/signup', function() {
    	FrontController::register();
  	});
  	$routes->post('/signup/new', function() {
    	UserController::create_new();
  	});
	$routes->get('/logout', function() {
  		FrontController::logout();
  	});


	
	function check_logged_in(){
		BaseController::check_logged_in();
	}
	
	$routes->get('/search', 'check_logged_in', function() {
		SearchController::search();
	});
	

	$routes->get('/profile', 'check_logged_in', function() {
		ProfileController::profile();
	});
	$routes->get('/users/user/:id', 'check_logged_in', function($id) {
		UserController::get($id);
	});
	$routes->get('/users/check_account', 'check_logged_in', function() {
		UserController::check_account();
	});
  	
  
  	$routes->get('/groups', 'check_logged_in', function() {
  		UsergroupController::groups();
  	});
	$routes->get('/groups/group/:id', 'check_logged_in', function($id) {
		UsergroupController::group($id);
	});
	$routes->post('/groups/edit/:id', 'check_logged_in', function($id) {
		UsergroupController::edit($id);
	});
	$routes->get('/groups/remove/:id', 'check_logged_in', function($id) {
		UsergroupController::remove($id);
	});
	$routes->get('/groups/new', 'check_logged_in', function() {
		UsergroupController::create_new_form();
	});
	$routes->post('/groups/new', 'check_logged_in', function() {
		UsergroupController::create_new();
	});
	

	$routes->get('/shops', 'check_logged_in', function() {
  		ShopController::shops();
  	});
	$routes->get('/shops/shop/:id', 'check_logged_in', function($id) {
		ShopController::shop($id);
	});
	$routes->post('/shops/edit/:id', 'check_logged_in', function($id) {
		ShopController::edit($id);
	});
	$routes->get('/shops/remove/:id', 'check_logged_in', function($id) {
		ShopController::remove($id);
	});
	$routes->get('/shops/new', 'check_logged_in', function() {
		ShopController::create_new_form();
	});
	$routes->post('/shops/new', 'check_logged_in', function() {
		ShopController::create_new();
	});
	
	
	$routes->get('/shoppinglists', 'check_logged_in', function() {
  		ShoppinglistController::shoppinglists();
  	});
	$routes->get('/shoppinglists/shoppinglist/:id', 'check_logged_in', function($id) {
		ShoppinglistController::shoppinglist($id);
	});
	$routes->post('/shoppinglists/edit/:id', 'check_logged_in', function($id) {
		ShoppinglistController::edit($id);
	});
	$routes->get('/shoppinglists/remove/:id', 'check_logged_in', function($id) {
		ShoppinglistController::remove($id);
	});
	$routes->get('/shoppinglists/new', 'check_logged_in', function() {
		ShoppinglistController::create_new_form();
	});
	$routes->post('/shoppinglists/new', 'check_logged_in', function() {
		ShoppinglistController::create_new();
	});
	

	
	
	
	$routes->post('/shopproducts/edit/:shop_id/:product_id', 'check_logged_in', function($shop_id, $product_id) {
		ShopProductController::edit($shop_id, $product_id);
	});
	$routes->post('/shopproducts/new/:shop_id', 'check_logged_in', function($shop_id) {
		ShopProductController::add($shop_id);
	});
	$routes->get('/shopproducts/remove/:shop_id/:product_id', 'check_logged_in', function($shop_id, $product_id) {
		ShopProductController::remove($shop_id, $product_id);
	});
	
	
	
	
	
	$routes->get('/products', 'check_logged_in', function() {
  		ProductController::products();
  	});
	$routes->get('/products/product/:id', 'check_logged_in', function($id) {
		ProductController::product($id);
	});
	$routes->post('/products/edit/:id', 'check_logged_in', function($id) {
		ProductController::edit($id);
	});
	$routes->get('/products/remove/:id', 'check_logged_in', function($id) {
		ProductController::remove($id);
	});
	$routes->get('/products/new', 'check_logged_in', function() {
		ProductController::create_new_form();
	});
	$routes->post('/products/new', 'check_logged_in', function() {
		ProductController::create_new();
	});
	
	
	$routes->get('/license', function() {
  		InfoController::license();
  	});