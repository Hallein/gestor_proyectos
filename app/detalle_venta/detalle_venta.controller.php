<?php

/****************************************************************************
*	getAll 		devuelve todos los detalle_ventas 							*
*	getOne 		devuelve un detalle_venta según el id 						*
*	store 		guarda un nuevo detalle_venta 								*
*	update 		actualiza un detalle_venta según el id 						*
*	delete 		elimina un detalle_venta según el id 						*
*****************************************************************************/
class DetalleVentaController{

	private $detalle_venta;

	public function __construct($db){
		$this->detalle_venta = new DetalleVenta($db);
	}

	public function getAllByVentaId($vid){		
		$result = $this->detalle_venta->getAllByVentaId($vid);
		return $result;
	}

}