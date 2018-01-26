<?php

/****************************************************************************
*	getAll 		devuelve todos los aluminios 								*
*	getOne 		devuelve un aluminio según el id 							*
*	store 		guarda un nuevo aluminio 									*
*	update 		actualiza un aluminio según el id 							*
*	delete 		elimina un aluminio según el id 							*
*****************************************************************************/
class AluminioController{

	private $aluminio;

	public function __construct($db){
		$this->aluminio = new Aluminio($db);
	}

	public function getAll(){		
		$result = $this->aluminio->getAll();
		return $result;
	}

	public function getOne($id){		
		return $this->aluminio->getOne($id);
	}

	public function store($data){
		return $this->aluminio->store($data);
	}

	public function update($id, $data){
		return $this->aluminio->update($id, $data);
	}

	public function delete($id){
		return $this->aluminio->delete($id);
	}

}