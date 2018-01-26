<?php

class Aluminio{

	private $db;

	public function __construct($db){
		$this->db = $db;
	}

	public function getAll(){

		$query = $this->db->prepare('	
				SELECT 	P.PID 			as id,
						P.PNOMBRE 		as nombre,
						P.PDESCRIPCION 	as descripcion,
						P.PPRECIOM2 	as valor_metro_cuadrado,
						P.PESPESOR 		as espesor
				FROM 	PRODUCTO P, ALUMINIO A
				WHERE 	P.PID = A.PID
				ORDER BY 	P.PNOMBRE ASC
		');

		if($query->execute()){
			return $query->fetchAll();
		}else{
			return array('status' => 'error');
		}
	}

	public function getOne($id){

		$query = $this->db->prepare('
				SELECT 	P.PID 			as id,
						P.PNOMBRE 		as nombre,
						P.PDESCRIPCION 	as descripcion,
						P.PPRECIOM2 	as valor_metro_cuadrado,
						P.PESPESOR 		as espesor
				FROM 	PRODUCTO P, ALUMINIO A
				WHERE 	P.PID = A.PID
				AND 	P.PID = :id
				ORDER BY 	P.PNOMBRE ASC
		');

		$query -> bindParam(':id', $id);

		if($query -> execute()){
			return $query->fetch();
		}else{
			return array('status' => 'error');
		}
	}

	public function store($data){
		$datos = array();
		$query = $this->db->prepare(' 	
			INSERT INTO PRODUCTO ( PNOMBRE, PDESCRIPCION, PPRECIOM2, PESPESOR )
			VALUES 	( :nombre, :descripcion, :valor_metro_cuadrado, :espesor ) 
		');
		
		$query -> bindParam(':nombre', 					$data['nombre']);
		$query -> bindParam(':descripcion', 			$data['descripcion']);
		$query -> bindParam(':valor_metro_cuadrado', 	$data['valor_metro_cuadrado']);
		$query -> bindParam(':espesor', 				$data['espesor']);

		if($query -> execute()){			
			$id = $this->db->lastInsertId();
			//Llamamos a la función para guardar los datos específicos del aluminio
			return $this->storeAluminio($id, $data);						
		}else{
			return array('status' => 'error', 'message' => 'Error al insertar el producto');
		}
	}

	private function storeAluminio($id, $data){
		$query = $this->db->prepare(' 	
			INSERT INTO Aluminio ( PID )
			VALUES 	( :id ) 
		');
		
		$query -> bindParam(':id', $id);

		if($query -> execute()){
			return array(
				'status' => 'success', 
				'id' => $id
			);
		}else{
			return array('status' => 'error', 'message' => 'Error al insertar el aluminio');
		}
	}

	public function update($id, $data){
		$datos = array();
		$query = $this->db->prepare('	UPDATE 	PRODUCTO 
										SET 	PNOMBRE 		= :nombre,
												PDESCRIPCION 	= :descripcion,
												PPRECIOM2 		= :valor_metro_cuadrado,
												PESPESOR 		= :espesor
										WHERE 	PID 			= :id');

		$query -> bindParam(':nombre', 					$data['nombre']);
		$query -> bindParam(':descripcion', 			$data['descripcion']);
		$query -> bindParam(':valor_metro_cuadrado', 	$data['valor_metro_cuadrado']);
		$query -> bindParam(':espesor', 				$data['espesor']);
		$query -> bindParam(':id', 						$id);

		if($query -> execute()){
			return $this->updateAluminio($id, $data);
		}else{
			return array('status' => 'error', 'message' => 'Error al actualizar el producto');
		}
	}

	//TODO cambiar campos a actualizar
	private function updateAluminio($id, $data){
		$datos = array();
		$query = $this->db->prepare('	UPDATE 	ALUMINIO 
										SET 	CDIBUJO = :dibujo,
												CTIPO 	= :tipo_cristal,
										WHERE 	PID 	= :id');

		$query -> bindParam(':dibujo', 			$data['dibujo']);
		$query -> bindParam(':tipo_cristal', 	$data['tipo_cristal']);
		$query -> bindParam(':id', 				$id);

		if($query -> execute()){
			return array(
				'status' => 'success', 
				'id' => $id
			);
		}else{
			return array('status' => 'error', 'message' => 'Error al actualizar el aluminio');
		}
	}

	private function delete($id){
		$query = $this->db->prepare('	DELETE FROM PRODUCTO
										WHERE PID = :id ');
		
		$query -> bindParam(':id', 	$id);

		if($query -> execute()){
			return array('status' => 'success');
		}else{
			return array('status' => 'error', 'message' => 'Error al eliminar el producto');
		}
	}

	public function deleteCristal($id){
		$query = $this->db->prepare('	DELETE FROM ALUMINIO
										WHERE PID = :id ');
		
		$query -> bindParam(':id', 	$id);

		if($query -> execute()){
			//Una vez borrado el aluminio, lo borramos de la tabla producto
			return $this->delete($id);
		}else{
			return array('status' => 'error', 'message' => 'Error al eliminar el aluminio');
		}
	}
		
}