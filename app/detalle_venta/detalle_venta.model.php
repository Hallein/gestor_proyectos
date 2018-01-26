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
						V.VID,						
						V.CANTIDAD,
						V.SUBTOTAL,
						V.PULIDO,
						V.CORTE_ESPECIAL,
						V.INSTALACION
				FROM 	DETALLE_VENTA V, PRODUCTO P
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
		$datos = array();
		$query = $this->db->prepare(' 	
			INSERT INTO DETALLE_VENTA ( VID, PID, CANTIDAD, SUBTOTAL, PULIDO, CORTE_ESPECIAL, INSTALACION )
			VALUES 	( :vid, :pid, :cantidad, :subtotal, :pulido, :corte_especial, :instalacion ) 
		');
		
		$query -> bindParam(':vid', 			$vid);
		$query -> bindParam(':pid', 			$data['pid']);
		$query -> bindParam(':cantidad', 		$data['cantidad']);
		$query -> bindParam(':subtotal', 		$data['subtotal']);
		$query -> bindParam(':pulido', 			$data['pulido']);
		$query -> bindParam(':corte_especial', 	$data['corte_especial']);
		$query -> bindParam(':instalacion', 	$data['instalacion']);

		if($query -> execute()){			
			return array('status' => 'success');					
		}else{
			return array('status' => 'error', 'message' => 'Error al insertar el detalle venta');
		}
	}
		
}