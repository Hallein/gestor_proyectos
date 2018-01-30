<?php

class Venta{

	private $db;
	private $detalle_venta;

	public function __construct($db){
		$this->db = $db;
		$this->detalle_venta = new DetalleVenta($db);
	}

	public function getAll(){

		$query = $this->db->prepare('	
				SELECT 	V.VID,
						V.VTOTAL,
						V.VFECHA
				FROM 	venta V
		');

		if($query->execute()){
			$result =  $query->fetchAll();

			//Verificamos que haya al menos una venta
			if(count($result) > 0){
				for($i = 0; $i < count($result); $i++){
					$result[$i]['detalle_venta'] = array();

					//Consultamos todos los detalles venta de $result[$i]['VID']
					$detalle = $this->detalle_venta->getAllByVentaId( $result[$i]['VID'] );
					if(count($detalle) > 0){

						//Recorrer el resultado de $detalle e ir ingresándolos a $result[$i]['detalle_venta'][] como array
						for($j = 0; $j < count($detalle); $j++){
							$result[$i]['detalle_venta'][] = $detalle[$j];
						}
					}
				}
			}

			return $result;

		}else{
			return array('status' => 'error');
		}
	}

	public function getOne($vid){

		$query = $this->db->prepare('
				SELECT 	V.VID,
						V.VTOTAL,
						V.VFECHA
				FROM 	venta V
				WHERE 	V.VID = :vid
		');

		$query -> bindParam(':vid', $vid);

		if($query -> execute()){
			$result =  $query->fetch();

			//Verificamos que haya al menos una venta
			if($result){
				
				$result['detalle_venta'] = array();

				//Consultamos todos los detalles venta de $vid
				$detalle = $this->detalle_venta->getAllByVentaId( $vid );
				if(count($detalle) > 0){

					//Recorrer el resultado de $detalle e ir ingresándolos a $result['detalle_venta'][] como array
					for($j = 0; $j < count($detalle); $j++){
						$result['detalle_venta'][] = $detalle[$j];
					}
				}
			}

			return $result;
		}else{
			return array('status' => 'error');
		}
	}

	public function store($data){
		$datos = array();

		//Calculamos en total de la venta
		$total = 0;
		foreach($data as $detalle){
			$total += intval( $detalle['subtotal'] );
		}

		//Creamos la venta
		$query = $this->db->prepare(' 	
			INSERT INTO venta ( UID, VTOTAL, VFECHA )
			VALUES 	( 1, :total, sysdate() ) 
		');
		
		$query -> bindParam(':total', $total);

		if($query -> execute()){			
			//Obtenemos el id de la venta insertada
			$vid = $this->db->lastInsertId();

			//Insertamos cada detalle por separado
			$insertados = 0;
			foreach($data as $detalle){
				$status = $this->detalle_venta->store($vid, $detalle);
				if($status['status'] === 'success'){
					$insertados++;
				}
			}

			//Revisamos si se insertaron correctamente todos los detalles venta
			if($insertados != count($data)){
				return array(
					'status' 	=> 'error', 
					'inserts' 	=> $insertados,
					'total' 	=> count($data)
				);
			}else{
				return array(
					'status' 	=> 'success', 
					'inserts' 	=> $insertados,
					'total' 	=> count($data)
				);
			}

							
		}else{
			return array('status' => 'error', 'message' => 'Error al insertar la venta');
		}
	}
		
}