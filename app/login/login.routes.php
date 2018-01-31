<?php

	/* Ruta que devuelve todos los logines */
	$app->post('/login', function ($request, $response, $args) {
		$credenciales = $request->getParsedBody();
		$json = $this->login->doLogin($credenciales);
		$response->write(json_encode($json));
		return $response;
	});

	/* Ruta que devuelve un login según su id */
	$app->get('/logout', function ($request, $response, $args) {
		$headers = $request->getHeaders();
		$json = $this->login->doLogout($headers);
		$response->write(json_encode($json));	
		return $request;
	});

//POST $data = $request->getParsedBody();
//filter_var($data['user'], FILTER_SANITIZE_STRING);

$app->get('/test/{password}', function($request, $response, $args){
	try {
		$password = $args['password'];

		$hash = password_hash ( $password, PASSWORD_BCRYPT );

    	$response->write(json_encode($hash));

	} catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
});