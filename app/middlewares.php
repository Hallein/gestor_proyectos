<?php 
	/* Función middleware que verifica si existe una sesión iniciada
	 * para autorizar el acceso a las rutas */	
	$login = function($request, $response, $next){
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
	};