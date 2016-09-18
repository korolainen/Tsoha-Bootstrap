<?php

  	$routes->get('/', function() {
    	HelloWorldController::index();
  	});

  	$routes->get('/hiekkalaatikko', function() {
    	HelloWorldController::sandbox();
  	});

  	
  
  	$routes->get('/groups', function() {
  		HelloWorldController::groups();
  	});
  	$routes->get('/group/1', function() {
  		HelloWorldController::group();
  	});

  	
  
  	$routes->get('/shoppinglists', function() {
  		HelloWorldController::groups();
  	});
  	$routes->get('/shoppinglists/1', function() {
  		HelloWorldController::group();
  	});

  	
  
  	$routes->get('/shops', function() {
  		HelloWorldController::shops();
  	});
  	$routes->get('/shops/1', function() {
  		HelloWorldController::shop();
  	});
  	
  	
  	
  	$routes->get('/login', function() {
  		HelloWorldController::login();
  	});