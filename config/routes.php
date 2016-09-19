<?php

  	$routes->get('/', function() {
    	HelloWorldController::index();
  	});
  	$routes->get('/forgotpass', function() {
    	HelloWorldController::forgotpass();
  	});
  	$routes->get('/signup', function() {
    	HelloWorldController::register();
  	});

  	
  
  	$routes->get('/groups', function() {
  		HelloWorldController::groups();
  	});
	$routes->get('/shoppinglists', function() {
  		HelloWorldController::groups();
  	});
	$routes->get('/shops', function() {
  		HelloWorldController::shops();
  	});
	$routes->get('/products', function() {
  		HelloWorldController::shops();
  	});
	$routes->get('/search', function() {
  		HelloWorldController::search();
  	});
	$routes->get('/license', function() {
  		HelloWorldController::license();
  	});
  	

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
	}