<?php
	use \Firebase\JWT\JWT;

$app->post('/login', function($request, $response, $args){

    $data = $request->getParsedBody();

    $username = filter_var($data['user'], FILTER_SANITIZE_STRING);
    $password = filter_var($data['password'], FILTER_SANITIZE_STRING);

    try {
    	$query = $this->db->prepare('	SELECT sigapusername, sigappassword, sigapusertype
    									FROM sigapuseradm WHERE sigapusername = :username');
		$query->bindParam(':username', $username, PDO::PARAM_STR);
		$query->execute();

		$user = $query->fetch();

		if($user){
			if(password_verify($password, $user['sigappassword'])){

		        $usuario['iat'] = time();
		        $usuario['exp'] = time() + (12 * 60 * 60);
		        $usuario['usr'] = $username;
		        $usuario['type'] = $user['sigapusertype'];

				$jwt = JWT::encode($usuario, $this->secret);
				$response->write(json_encode(
					array(
						"status" => "success",
						"title" => "Sesión iniciada",
						"message" => "Bienvenido a SIGAP",
						"token" => $jwt
					)
				));
				// guardar token en la bd
				$query = $this->db->prepare('UPDATE sigapuseradm SET sigaptoken = :token
											 WHERE sigapusername = :username');
				$query->bindParam(':token', $jwt, PDO::PARAM_STR);
				$query->bindParam(':username', $username, PDO::PARAM_STR);
				$query->execute();
			}else{
				$respuesta['status'] = "error";
				$respuesta['title'] = "Error al iniciar sesión";
    			$respuesta['message'] = 'Usuario o password incorrectos';
    			$response->write(json_encode($respuesta));
			}
		} else {
            $respuesta['status'] = "error";
			$respuesta['title'] = "Error al iniciar sesión";
            $respuesta['message'] = 'Usuario o password incorrectos';
            $response->write(json_encode($respuesta));
        }
		
		$this->db = null;
    	return $response;

	} catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}

});

$app->get('/logout', function($request, $response, $args){
	try {
		$headers = $request->getHeaders();
        $req = split(" ", $headers['HTTP_AUTHORIZATION'][0]);
        $token = $req[1];
		$jwt = JWT::decode($token, $this->secret, array('HS256'));
		$faketoken = md5(uniqid(rand(), true));

		$query = $this->db->prepare('UPDATE sigapuseradm SET sigaptoken = :faketoken
									 WHERE sigapusername = :username');
		$query->bindParam(':faketoken', $faketoken, PDO::PARAM_STR);
		$query->bindParam(':username', $jwt->usr, PDO::PARAM_STR);

		$query->execute();

	    $logout = array();
	    $logout["success"] = "success";
	    $logout["status"] = "info";
		$logout['title'] = "Sesión terminada";
	    $logout["message"] = "¡Hasta pronto!";

		$response->write(json_encode($logout));
    	return $response;

	} catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
});


$app->get('/test/{password}', function($request, $response, $args){
	try {
		$password = $args['password'];
		$hash = new passwordHash();
		$newpass = $hash->hash($password);

		$response->write($newpass);
    	return $response;

	} catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
});

$app->get('/verify/{pass}/{hash}', function($request, $response, $args){
	try {
		$hash = $args['hash'];
		$pass = $args['pass'];
		$verify = new passwordHash();
		$newpass = $verify->check_password($hash, $pass);
		$success = array();

		if($newpass){
			$success['success'] = 1;
		}else{
			$success['success'] = 0;
		}

		$response->write(json_encode($success));
    	return $response;

	} catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
});