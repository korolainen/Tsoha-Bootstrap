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
  	

	if(array_key_exists('no', $_GET)){
		$routes->get('/groups/'.intval($_GET['no']), function() {
			HelloWorldController::group();
		});
		$routes->get('/shoppinglists/'.intval($_GET['no']), function() {
			HelloWorldController::group();
		});
		$routes->get('/shops/'.intval($_GET['no']), function() {
			HelloWorldController::shop();
		});
	}