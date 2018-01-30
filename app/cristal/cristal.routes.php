<?php

$app->group('/cristal', function () {

	/* Ruta que devuelve todos los cristales */
	$this->get('', function ($request, $response, $args) {
		$json = $this->cristal->getAll();
		$response->write(json_encode($json));
		return $response;
	});

	/* Ruta que devuelve un cristal según su id */
	$this->get('/{id}', function ($request, $response, $args) {

		$id = filter_var($args['id'], FILTER_SANITIZE_STRING);
		$json = $this->cristal->getOne($id);
		$response->write(json_encode($json));	
		return $response;
	});

	/* Ruta que crea un nuevo cristal */					/*======= Datos POST =======*/
	$this->post('', function ($request, $response, $args) {	/* - nombre					*/
		$data = $request->getParsedBody();					/* - descripcion			*/
		$json = $this->cristal->store($data);				/* - valor_metro_cuadrado	*/
		$response->write(json_encode($json));				/* - espesor				*/
		return $response;									/* - dibujo					*/
	});														/* - tipo_cristal			*/

	/* Ruta que actualiza un cristal según su id */
	$this->post('/update/{id}', function ($request, $response, $args) {
		$id = filter_var($args['id'], FILTER_SANITIZE_STRING);
		$data = $request->getParsedBody();
		//$json = $this->cristal->update($id, $data);
		//$response->write(json_encode($json));	
		$response->write(json_encode($data));	//Descomentar para usar
		return $response;
	});

	/* Ruta que elimina un cristal según su id */
	$this->post('/delete/{id}', function ($request, $response, $args) {
		$id = filter_var($args['id'], FILTER_SANITIZE_STRING);
		//$json = $this->cristal->delete($id);
		//$response->write(json_encode($json));	
		$response->write(json_encode(array('id' => $id)));	//Descomentar para usar
		return $response;
	});
});

//POST $data = $request->getParsedBody();
//filter_var($data['user'], FILTER_SANITIZE_STRING);