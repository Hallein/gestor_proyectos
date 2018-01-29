<?php 

/*
	composer.json
        "firebase/php-jwt": "^3.0",
        "tuupola/slim-jwt-auth": "^2.3",
*/

	// Middleware para ver si existe el token en la bd (2do en ejecutarse)
// (al hacer logout, el token asociado al usuario se debe borrar de la base de datos)
$mw = function ($request, $response, $next) {
        $headers = $request->getHeaders();
        $req = explode(" ", $headers['HTTP_AUTHORIZATION'][0]);
        if(isset($req[1])){
            $token = $req[1];
        }
        $query = $this->db->prepare('   SELECT sigaptoken
                                        FROM sigapuseradm WHERE sigaptoken = :token');
        $query->bindParam(':token', $token, PDO::PARAM_STR);
        $query->execute();

        $user = $query->fetch();

        if($user){
            $response = $next($request, $response);
        }
        else{
            //Token inválido
            $data["status"] = "error";
            $data["message"] = "Invalid Token";
            $response->getBody()->write(json_encode($data));
            return $response->withStatus(401);
        }
        return $response;
        
    };

//Middleware que intercepta todas las request y verifique el JWT (1ro en ejecutarse)
$app->add(new \Slim\Middleware\JwtAuthentication([
    "path" => ["/pacientes/", "/agenda", "/servicios", "/tratamientos"],
    "secret" => $container["secret"],
    "secure" => false,
    "error" => function ($request, $response, $arguments) {
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
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