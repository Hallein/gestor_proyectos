<?php

/****************************************************************************
*	getAll 		devuelve todos los ventas 									*
*	getOne 		devuelve un venta según el id 								*
*	store 		guarda un nuevo venta 										*
*	update 		actualiza un venta según el id 								*
*	delete 		elimina un venta según el id 								*
*****************************************************************************/
class VentaController{

	private $venta;

	public function __construct($db){
		$this->venta = new Venta($db);
	}

	public function getAll(){		
		$result = $this->venta->getAll();
		return $result;
	}

	public function getOne($id){		
		return $this->venta->getOne($id);
	}

	public function store($data){
		return $this->venta->store($data);
	}

}