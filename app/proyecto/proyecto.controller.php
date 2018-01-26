<?php

/****************************************************************************
*	getAll 		devuelve todos los proyectos 								*
*	getOne 		devuelve un proyecto según el id 							*
*	store 		guarda un nuevo proyecto 									*
*	update 		actualiza un proyecto según el id 							*
*	delete 		elimina un proyecto según el id 							*
*****************************************************************************/
class ProyectoController{

	private $proyecto;

	public function __construct($db){
		$this->proyecto = new Proyecto($db);
	}

	public function getAll(){		
		$result = $this->proyecto->getAll();
		return $result;
	}

	public function getAllByState($estado){		
		$result = $this->proyecto->getAllByState($estado);
		return $result;
	}

	public function getOne($id){		
		return $this->proyecto->getOne($id);
	}

	public function store($data){
		return $this->proyecto->store($data);
	}

	public function update($id, $data){
		return $this->proyecto->update($id, $data);
	}

	public function updateStateToInProgress($id){
		return $this->proyecto->updateStateToInProgress($id);
	}

	public function updateStateToFinished($id){
		return $this->proyecto->updateStateToFinished($id);
	}

	public function delete($id){
		return $this->proyecto->delete($id);
	}

}