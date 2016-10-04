<?php

  class DB{

  	private static $conn = null; 
      public static function connection(){
        if(self::is_connected()) return self::$conn;
        // Haetaan tietokantakonfiguraatio
        $connection_config = DatabaseConfig::connection_config();
        $config = $connection_config['config'];        
        
        try {
            // Alustetaan PDO
            if(isset($config['username'])){
              $connection = new PDO($config['resource'], $config['username'], $config['password']);
            }else{
              $connection = new PDO($config['resource']);
            }
             // Asetetaan tietokannan kenttien koodaukseksi utf8
            $connection->exec('SET NAMES UTF8');

            // Näytetään virheilmoitukset
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            self::$conn = $connection;
        } catch (PDOException $e) {
            die('Virhe tietokantayhteydessä tai tietokantakyselyssä: ' . $e->getMessage());
        }

        return $connection;
      }
      
	private static function is_connected(){
		if(self::$conn==null) return false;
		try {
			return self::$conn->prepare("SELECT 1")->execute()!==false;
		} catch (PDOException $e) {
		}
		return false;
	}
	
      public static function test_connection(){
        require 'vendor/ConnectionTest/connection_test.php';

        exit();
      }

  }
