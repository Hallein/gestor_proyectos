<?php

/****************************************************************************
*	getAll 		devuelve todos los cristales 								*
*	getOne 		devuelve un cristal según el id 							*
*	store 		guarda un nuevo cristal 									*
*	update 		actualiza un cristal según el id 							*
*	delete 		elimina un cristal según el id 								*
*****************************************************************************/
class CristalController{

	private $cristal;

	public function __construct($db){
		$this->cristal = new Cristal($db);
	}

	public function getAll(){		
		$result = $this->cristal->getAll();
		return $result;
	}

	public function getOne($id){		
		return $this->cristal->getOne($id);
	}

	public function store($data){
		return $this->cristal->store($data);
	}

	public function update($id, $data){
		return $this->cristal->update($id, $data);
	}

	public function delete($id){
		return $this->cristal->delete($id);
	}

}