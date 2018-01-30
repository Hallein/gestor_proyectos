<?php

class DetalleVenta{

	private $db;

	public function __construct($db){
		$this->db = $db;
	}

	public function getAllByVentaId($vid){

		$query = $this->db->prepare('	
				SELECT  V.PID,
						P.PNOMBRE,
						P.PDESCRIPCION,
						P.PPRECIOM2,
						P.PESPESOR,
						P.PTIPO,
						V.VID,						
						V.CANTIDAD,
						V.SUBTOTAL,
						V.PULIDO,
						V.CORTE_ESPECIAL,
						V.INSTALACION,
						V.DETALLE
				FROM 	detalle_venta V, producto P
				WHERE 	V.PID = P.PID
				AND		V.VID = :vid
		');

		$query -> bindParam(':vid', $vid);

		if($query->execute()){
			return $query->fetchAll();
		}else{
			return array('status' => 'error');
		}
	}

	public function store($vid, $data){
		$pid 			= intval(filter_var($data['pid'], FILTER_SANITIZE_STRING));
		$cantidad 		= intval(filter_var($data['quantity'], FILTER_SANITIZE_STRING));
		$subtotal 		= intval(filter_var($data['subtotal'], FILTER_SANITIZE_STRING));
		$pulido 		= intval(filter_var($data['polished'], FILTER_SANITIZE_STRING));
		$corte_especial = intval(filter_var($data['slice'], FILTER_SANITIZE_STRING));
		$instalacion 	= intval(filter_var($data['instalation'], FILTER_SANITIZE_STRING));
		$detalle 		= filter_var($data['detail'], FILTER_SANITIZE_STRING);

		$datos = array();
		$query = $this->db->prepare(' 	
			INSERT INTO detalle_venta ( VID, PID, CANTIDAD, SUBTOTAL, PULIDO, CORTE_ESPECIAL, INSTALACION, DETALLE )
			VALUES 	( :vid, :pid, :cantidad, :subtotal, :pulido, :corte_especial, :instalacion, :detalle ) 
		');
		
		$query -> bindParam(':vid', 			$vid);
		$query -> bindParam(':pid', 			$pid);
		$query -> bindParam(':cantidad', 		$cantidad);
		$query -> bindParam(':subtotal', 		$subtotal);
		$query -> bindParam(':pulido', 			$pulido);
		$query -> bindParam(':corte_especial', 	$corte_especial);
		$query -> bindParam(':instalacion', 	$instalacion);
		$query -> bindParam(':detalle', 		$detalle);

		if($query -> execute()){			
			return array('status' => 'success');					
		}else{
			return array('status' => 'error', 'message' => 'Error al insertar el detalle venta');
		}
	}
		
}