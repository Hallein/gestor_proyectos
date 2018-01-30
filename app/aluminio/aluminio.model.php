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
						P.PESPESOR 		as espesor,
						P.PTIPO 		as tipo,
						A.ATIPO_LINEA 	as tipo_linea,
						A.ACOLOR 		as color,
						A.ATIPO_VIDRIO 	as tipo_vidrio,
						A.AVALOR_MINIMO as valor_minimo,
						A.ADIBUJO 		as dibujo
				FROM 	producto P, aluminio A
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
						P.PESPESOR 		as espesor,
						P.PTIPO 		as tipo,
						A.ATIPO_LINEA 	as tipo_linea,
						A.ACOLOR 		as color,
						A.ATIPO_VIDRIO 	as tipo_vidrio,
						A.AVALOR_MINIMO as valor_minimo,
						A.ADIBUJO 		as dibujo
				FROM 	producto P, aluminio A
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
		$nombre 				= filter_var($data['nombre'], FILTER_SANITIZE_STRING);
		$descripcion 			= filter_var($data['descripcion'], FILTER_SANITIZE_STRING);
		$valor_metro_cuadrado 	= intval(filter_var($data['valor_metro_cuadrado'], FILTER_SANITIZE_STRING));
		$espesor 				= intval(filter_var($data['espesor'], FILTER_SANITIZE_STRING));

		$datos = array();
		$query = $this->db->prepare(' 	
			INSERT INTO producto ( PNOMBRE, PDESCRIPCION, PPRECIOM2, PESPESOR, PTIPO )
			VALUES 	( :nombre, :descripcion, :valor_metro_cuadrado, :espesor, 2 ) 
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
		$tipo_linea 	= filter_var($data['tipo_linea'], FILTER_SANITIZE_STRING);
		$color 			= filter_var($data['color_aluminio'], FILTER_SANITIZE_STRING);
		$tipo_vidrio 	= filter_var($data['tipo_vidrio'], FILTER_SANITIZE_STRING);
		$valor_minimo 	= intval(filter_var($data['valor_minimo'], FILTER_SANITIZE_STRING));
		$dibujo 		= filter_var($data['dibujo'], FILTER_SANITIZE_STRING);

		$query = $this->db->prepare(' 	
			INSERT INTO aluminio ( PID, ATIPO_LINEA, ACOLOR, ATIPO_VIDRIO, AVALOR_MINIMO, ADIBUJO )
			VALUES 	( :id, :tipo_linea, :color, :tipo_vidrio, :valor_minimo, :dibujo ) 
		');
		
		$query -> bindParam(':id', 				$id);
		$query -> bindParam(':tipo_linea', 		$tipo_linea);
		$query -> bindParam(':color', 			$color);
		$query -> bindParam(':tipo_vidrio', 	$tipo_vidrio);
		$query -> bindParam(':valor_minimo', 	$valor_minimo);
		$query -> bindParam(':dibujo', 			$dibujo);

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
		$query = $this->db->prepare('	UPDATE 	producto 
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
		$query = $this->db->prepare('	UPDATE 	aluminio 
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
		$query = $this->db->prepare('	DELETE FROM producto
										WHERE PID = :id ');
		
		$query -> bindParam(':id', 	$id);

		if($query -> execute()){
			return array('status' => 'success');
		}else{
			return array('status' => 'error', 'message' => 'Error al eliminar el producto');
		}
	}

	public function deleteCristal($id){
		$query = $this->db->prepare('	DELETE FROM aluminio
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