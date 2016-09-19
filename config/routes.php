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
	$routes->get('/profile', function() {
  		HelloWorldController::profile();
  	});
	$routes->get('/logout', function() {
  		HelloWorldController::logout();
  	});
	
		$routes->get('/groups/1', function() {
			HelloWorldController::group();
		});
		$routes->get('/shoppinglists/1', function() {
			HelloWorldController::shoppinglist();
		});
		$routes->get('/stores/1', function() {
			HelloWorldController::store();
		});
		$routes->get('/products/1', function() {
			HelloWorldController::product();
		});
	/*
	if(array_key_exists('no', $_GET)){
		$routes->get('/groups/'.intval($_GET['no']), function() {
			HelloWorldController::group();
		});
		$routes->get('/shoppinglists/'.intval($_GET['no']), function() {
			HelloWorldController::shoppinglist();
		});
		$routes->get('/stores/'.intval($_GET['no']), function() {
			HelloWorldController::store();
		});
		$routes->get('/products/'.intval($_GET['no']), function() {
			HelloWorldController::product();
		});
	}*/