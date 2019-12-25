<?php

require_once(ROOT.'/models/FirstModel.php');
class FirstController{

	public static $login;

	public function __construct(){
		self::$login = FirstModel::Authorization();
	}
}