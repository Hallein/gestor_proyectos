<?php 

/*
	composer.json
        "firebase/php-jwt": "^3.0",
        "tuupola/slim-jwt-auth": "^2.3.3",
*/

	// Middleware para ver si existe el token en la bd (2do en ejecutarse)
// (al hacer logout, el token asociado al usuario se debe borrar de la base de datos)
$mw = function ($request, $response, $next) {
    $headers = $request->getHeaders();
    $token = explode(" ", $headers['HTTP_AUTHORIZATION'][0]);

    /* Si no está seteado el token, retornar error */
    if( !isset($token[1]) ){
        $data["status"] = "token_error";
        $data["message"] = "Token inválido";
        $response->getBody()->write(json_encode($data));
        return $response->withStatus(401);
    }
    
    $query = $this->db->prepare('   SELECT TOKEN
                                    FROM usuario WHERE TOKEN = :token');

    $query->bindParam(':token', $token[1], PDO::PARAM_STR);
    $query->execute();

    if( $query->fetch() ){
        $response = $next($request, $response);
    }
    else{
        //Token inválido
        $data["status"] = "token_error";
        $data["message"] = "Token inválido";
        $response->getBody()->write(json_encode($data));
        return $response->withStatus(401);
    }
    return $response;
    
};

//Middleware que intercepta todas las request y verifique el JWT (1ro en ejecutarse)
$app->add(new \Slim\Middleware\JwtAuthentication([
    "path" => ["/cristal", "/aluminio", "/venta", "/logout"],
    "secret" => 'Khao863a0s98dhna90a45s3',
    "secure" => false,
    "error" => function ($request, $response, $arguments) {
        $data["status"] = "token_error";
        $data["message"] = 'Acceso denegaddo';
        //$data["message"] = $arguments["message"];
        return $response
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]));

	/* Función middleware que verifica si existe una sesión iniciada
	 * para autorizar el acceso a las rutas */	
	/*$login = function($request, $response, $next){
		//antes
		if( !isset($_SESSION['session']) || empty($_SESSION['session']) ){
			$no_autorizado = array(
								'status' 	=>'error', 
								'code' 		=>'401', 
								'message' 	=>'no autorizado'
							);
			$response->getBody()->write(json_encode($no_autorizado));
		}else{
		//seguir con la ruta
			$response = $next($request, $response);
		}
		
		return $response;
	};*/