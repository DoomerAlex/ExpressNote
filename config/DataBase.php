<?php

class DataBase{
	public static function getConnectDb(){
		$params = include(ROOT. '/config/DB_access.php');
		$dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
		$opt = [
			PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES		=> false,
		];
		$pdo = new PDO($dsn, $params['user'], $params['password'], $opt);
		return $pdo;
	}	
}