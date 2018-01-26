<?php

$app->group('/venta', function () {

	/* Ruta que devuelve todos los ventas */
	$this->get('', function ($request, $response, $args) {
		$json = $this->venta->getAll();
		$response->write(json_encode($json));
		return $response;
	});

	/* Ruta que devuelve un venta según su id */
	$this->get('/{id}', function ($request, $response, $args) {

		$id = filter_var($args['id'], FILTER_SANITIZE_STRING);
		$json = $this->venta->getOne($id);
		$response->write(json_encode($json));	
		return $response;
	});

	/* Ruta que crea un nuevo venta */
	$this->post('', function ($request, $response, $args) {
		$data = $request->getParsedBody(); //Array detalle venta		
		$json = $this->venta->store($data);					
		$response->write(json_encode($json));				
		return $response;									
	});														

});

//POST $data = $request->getParsedBody();
//filter_var($data['user'], FILTER_SANITIZE_STRING);