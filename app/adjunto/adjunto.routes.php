<?php

$app->group('/adjunto', function () {

	/* Ruta que elimina un adjunto según su id */
	$this->delete('/{id}', function ($request, $response, $args) {
		$id = filter_var($args['id'], FILTER_SANITIZE_STRING);
		$json = $this->adjunto->delete($id);
		$response->write(json_encode($json));	
		return $response;
	});
});

//POST $data = $request->getParsedBody();
//filter_var($data['user'], FILTER_SANITIZE_STRING);