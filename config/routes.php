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
  		HelloWorldController::shoppinglists();
  	});
	$routes->get('/stores', function() {
  		HelloWorldController::stores();
  	});
	$routes->get('/products', function() {
  		HelloWorldController::products();
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
		$routes->get('/stores/'.intval($_GET['no']), function() {
			HelloWorldController::stores();
		});
		$routes->get('/products/'.intval($_GET['no']), function() {
			HelloWorldController::product();
		});
	}