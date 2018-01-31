<?php

class LoginController{

	private $login;

	public function __construct($db){
		$this->login = new Login($db);
	}

	public function doLogin($credenciales){		
		if(!isset($credenciales['username']) || !isset($credenciales['password']))
			return array(
				'status' 	=> 'error', 
				'message' 	=> 'usuario o contraseña incorrectos'
			);

		$username = filter_var($credenciales['username'], FILTER_SANITIZE_STRING);
    	$password = filter_var($credenciales['password'], FILTER_SANITIZE_STRING);

		return $this->login->doLogin($username, $password);
	}

}