<?php

	$routes->get('/', function() {
		FrontController::login_page();
	});
  	$routes->post('/login', function() {
    	ProfileController::login();
  	});
  	/*
  	$routes->get('/forgotpass', function() {
    	FrontController::forgotpass();
  	});
  	*/
  	$routes->get('/signup', function() {
    	FrontController::register();
  	});
  	$routes->post('/signup/new', function() {
    	UserController::create_new();
  	});
	$routes->get('/logout', function() {
  		FrontController::logout();
  	});

	$routes->notFound(function () {
		FrontController::error404();
	});
	
	function check_logged_in(){
		BaseController::check_logged_in();
	}

	$routes->get('/search', 'check_logged_in', function() {
		SearchController::search();
	});
	$routes->get('/search/products', 'check_logged_in', function() {
		ProductController::find();
	});
	$routes->get('/search/shoppinglists', 'check_logged_in', function() {
		ShoppinglistController::find();
	});
	$routes->get('/search/shops', 'check_logged_in', function() {
		ShopController::find();
	});
	$routes->get('/search/groups', 'check_logged_in', function() {
		UsergroupController::find();
	});
	

	$routes->get('/profile', 'check_logged_in', function() {
		ProfileController::profile();
	});
	$routes->post('/profile/edit', 'check_logged_in', function() {
		ProfileController::edit();
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
	

	
	
	
	
	$routes->post('/productshop/new/:product_id', 'check_logged_in', function($product_id) {
		ProductShopController::add($product_id);
	});
	

	
	
	
	
	$routes->post('/shoppinglistproducts/edit/:shop_id/:product_id', 'check_logged_in', function($shoppinglist_id, $product_id) {
		ShoppinglistProductController::edit($shoppinglist_id, $product_id);
	});
	$routes->post('/shoppinglistproducts/new/:shop_id', 'check_logged_in', function($shoppinglist_id) {
		ShoppinglistProductController::add($shoppinglist_id);
	});
	$routes->get('/shoppinglistproducts/remove/:shop_id/:product_id', 'check_logged_in', function($shoppinglist_id, $product_id) {
		ShoppinglistProductController::remove($shoppinglist_id, $product_id);
	});
	

	
	
	
	$routes->post('/usergroupusers/new/:group_id', 'check_logged_in', function($group_id) {
		UsergroupUserController::add($group_id);
	});
	$routes->get('/usergroupusers/remove/:group_id/:users_id', 'check_logged_in', function($group_id, $users_id) {
		UsergroupUserController::remove($group_id, $users_id);
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
	
	
	
	
	$routes->get('/shops/link/:id', 'check_logged_in', function($shop_id) {
		ShopController::link($shop_id);
	});
	$routes->get('/shoppinglists/link/:id', 'check_logged_in', function($shop_id) {
		ShoppinglistController::link($shop_id);
	});
	
	
	$routes->get('/license', function() {
  		InfoController::license();
  	});
	
	
	
	
	
	/**
	 * Nämä on sitä varten, että yhteys on katkennut post-vaiheessa, 
	 * ja käyttäjä yrittää sivulle menemistä uudelleen
	 * */
	$routes->get('/login', function() {
		ProfileController::login();
	});
	$routes->get('/signup/new', function() {
		Redirect::back('/signup');
	});
	$routes->get('/profile/edit', 'check_logged_in', function() {
		Redirect::back('/profile');
	});
	$routes->get('/groups/edit/:id', 'check_logged_in', function($id) {
		Redirect::back('/groups/group/'.$id);
	});
	$routes->get('/shops/edit/:id', 'check_logged_in', function($id) {
		Redirect::back('/shops/shop/'.$id);
	});
	$routes->get('/shoppinglists/edit/:id', 'check_logged_in', function($id) {
		Redirect::back('/shoppinglists/shoppinglist/'.$id);
	});
	$routes->get('/shopproducts/edit/:shop_id/:product_id', 'check_logged_in', function($shop_id, $product_id) {
		Redirect::back('/shops/shop/'.$shop_id);
	});
	$routes->get('/shopproducts/new/:shop_id', 'check_logged_in', function($shop_id) {
		Redirect::back('/shops/shop/'.$shop_id);
	});
	$routes->get('/productshop/new/:product_id', 'check_logged_in', function($product_id) {
		Redirect::back('/products/product/'.$product_id);
	});
	$routes->get('/shoppinglistproducts/edit/:shop_id/:product_id', 'check_logged_in', function($shoppinglist_id, $product_id) {
		Redirect::back('/shoppinglists/shoppinglist/'.$shoppinglist_id);
	});
	$routes->get('/shoppinglistproducts/new/:shop_id', 'check_logged_in', function($shoppinglist_id) {
		Redirect::back('/shoppinglists/shoppinglist/'.$shoppinglist_id);
	});
	$routes->get('/usergroupusers/new/:group_id', 'check_logged_in', function($group_id) {
		Redirect::back('/groups/group/'.$group_id);
	});
	$routes->get('/products/edit/:id', 'check_logged_in', function($id) {
		Redirect::back('/products/product/'.$id);
	});