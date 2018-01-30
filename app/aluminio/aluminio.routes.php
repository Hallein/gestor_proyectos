<?php

$app->group('/aluminio', function () {

	/* Ruta que devuelve todos los aluminios */
	$this->get('', function ($request, $response, $args) {
		$json = $this->aluminio->getAll();
		$response->write(json_encode($json));
		return $response;
	});

	/* Ruta que devuelve un aluminio según su id */
	$this->get('/{id}', function ($request, $response, $args) {

		$id = filter_var($args['id'], FILTER_SANITIZE_STRING);
		$json = $this->aluminio->getOne($id);
		$response->write(json_encode($json));	
		return $response;
	});

	/* Ruta que crea un nuevo aluminio */					/*======= Datos POST =======*/
	$this->post('', function ($request, $response, $args) {	/* - nombre					*/
		$data = $request->getParsedBody();					/* - descripcion			*/
		$json = $this->aluminio->store($data);				/* - valor_metro_cuadrado	*/
		$response->write(json_encode($json));				/* - espesor				*/
		return $response;									/* - dibujo					*/
	});														/* - tipo_aluminio			*/

	/* Ruta que actualiza un aluminio según su id */
	$this->post('/update/{id}', function ($request, $response, $args) {
		$id = filter_var($args['id'], FILTER_SANITIZE_STRING);
		$data = $request->getParsedBody();
		//$json = $this->aluminio->update($id, $data);
		//$response->write(json_encode($json));	
		$response->write(json_encode($data));	//Descomentar para usar
		return $response;
	});

	/* Ruta que elimina un aluminio según su id */
	$this->post('/delete/{id}', function ($request, $response, $args) {
		$id = filter_var($args['id'], FILTER_SANITIZE_STRING);
		//$json = $this->aluminio->delete($id);
		//$response->write(json_encode($json));	
		$response->write(json_encode(array('id' => $id)));	//Descomentar para usar
		return $response;
	});
});

//POST $data = $request->getParsedBody();
//filter_var($data['user'], FILTER_SANITIZE_STRING);