<?php

$app->group('/proyecto', function () {

	/* Ruta que devuelve todos los proyectos */
	$this->get('', function ($request, $response, $args) {
		$json = $this->proyecto->getAll();
		$response->write(json_encode($json));
		return $response;
	});

	/* Ruta que devuelve todos los proyectos en cola */
	$this->get('/cola', function ($request, $response, $args) {
		$json = $this->proyecto->getAllByState(1);
		$response->write(json_encode($json));
		return $response;
	});

	/* Ruta que devuelve todos los proyectos en proceso */
	$this->get('/proceso', function ($request, $response, $args) {
		$json = $this->proyecto->getAllByState(2);
		$response->write(json_encode($json));
		return $response;
	});

	/* Ruta que devuelve todos los proyectos terminados */
	$this->get('/terminado', function ($request, $response, $args) {
		$json = $this->proyecto->getAllByState(3);
		$response->write(json_encode($json));
		return $response;
	});

	/* Ruta que devuelve un proyecto según su id */
	$this->get('/{id}', function ($request, $response, $args) {

		$id = filter_var($args['id'], FILTER_SANITIZE_STRING);
		$json = $this->proyecto->getOne($id);
		$response->write(json_encode($json));	
		return $response;
	});

	/* Ruta que crea un nuevo proyecto */
	$this->post('', function ($request, $response, $args) {
		$data = $request->getParsedBody();
		$json = $this->proyecto->store($data);
		$response->write(json_encode($json));	
		return $response;
	});

	/* Ruta que actualiza un proyecto según su id */
	$this->put('/{id}', function ($request, $response, $args) {
		$id = filter_var($args['id'], FILTER_SANITIZE_STRING);
		$data = $request->getParsedBody();
		$json = $this->proyecto->update($id, $data);
		$response->write(json_encode($json));	
		return $response;
	});

	/* Ruta que actualiza el estado de un proyecto a en proceso según su id */
	$this->put('/estado/proceso/{id}', function ($request, $response, $args) {
		$id = filter_var($args['id'], FILTER_SANITIZE_STRING);
		$data = $request->getParsedBody();
		$json = $this->proyecto->updateStateToInProgress($id);
		$response->write(json_encode($json));	
		return $response;
	});

	/* Ruta que actualiza el estado de un proyecto a terminado según su id */
	$this->put('/estado/terminado/{id}', function ($request, $response, $args) {
		$id = filter_var($args['id'], FILTER_SANITIZE_STRING);
		$data = $request->getParsedBody();
		$json = $this->proyecto->updateStateToFinished($id);
		$response->write(json_encode($json));	
		return $response;
	});

	/* Ruta que elimina un proyecto según su id */
	$this->delete('/{id}', function ($request, $response, $args) {
		$id = filter_var($args['id'], FILTER_SANITIZE_STRING);
		$json = $this->proyecto->delete($id);
		$response->write(json_encode($json));	
		return $response;
	});
});

//POST $data = $request->getParsedBody();
//filter_var($data['user'], FILTER_SANITIZE_STRING);