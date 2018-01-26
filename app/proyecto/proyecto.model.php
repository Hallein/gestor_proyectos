<?php

class Proyecto{

	private $db;
	private $adjunto;

	public function __construct($db){
		$this->db = $db;
		$this->adjunto = new Adjunto($db);
	}

	public function getAll(){

		$query = $this->db->prepare('	
				SELECT 	ID_PROYECTO,
						NOMBRE_PROYECTO,
						DESCRIPCION_PROYECTO,
						CREADOR,
						TELEFONO_CREADOR,
						EMAIL_CREADOR,
						FECHA_INGRESO,
						FECHA_IMPRESION,
						FECHA_ENTREGA,
						ESTADO
				FROM 	PROYECTO P
				ORDER BY 	ESTADO ASC,
							FECHA_INGRESO ASC
		');

		if($query->execute()){
			$result = $query->fetchAll();

			if(count($result) > 0){
				for($i = 0; $i < count($result); $i++){
					$result[$i]['adjuntos'] = array();
					//Consultar todos los adjuntos de $result[$i]['ID_PROYECTO']
					$adjuntos = $this->adjunto->getAllById( $result[$i]['ID_PROYECTO'] );

					if(count($adjuntos) > 0){
						//Recorrer el resultado de adjuntos e ir ingresándolos a $result[$i]['adjuntos'][] como array
						for($j = 0; $j < count($adjuntos); $j++){
							$result[$i]['adjuntos'][] = $adjuntos[$j];
						}
					}

				}
			}
			return $result;
		}else{
			return array('status' => 'error');
		}
	}

	public function getAllByState($estado){

		$query = $this->db->prepare('	
				SELECT 	ID_PROYECTO,
						NOMBRE_PROYECTO,
						DESCRIPCION_PROYECTO,
						CREADOR,
						TELEFONO_CREADOR,
						EMAIL_CREADOR,
						FECHA_INGRESO,
						FECHA_IMPRESION,
						FECHA_ENTREGA,
						ESTADO
				FROM 	PROYECTO P
				WHERE 	ESTADO = :estado
				ORDER BY 	ESTADO ASC,
							FECHA_INGRESO ASC
		');

		$query -> bindParam(':estado', $estado);

		if($query->execute()){
			$result = $query->fetchAll();

			if(count($result) > 0){
				for($i = 0; $i < count($result); $i++){
					$result[$i]['adjuntos'] = array();
					//Consultar todos los adjuntos de $result[$i]['ID_PROYECTO']
					$adjuntos = $this->adjunto->getAllById( $result[$i]['ID_PROYECTO'] );

					if(count($adjuntos) > 0){
						//Recorrer el resultado de adjuntos e ir ingresándolos a $result[$i]['adjuntos'][] como array
						for($j = 0; $j < count($adjuntos); $j++){
							$result[$i]['adjuntos'][] = $adjuntos[$j];
						}
					}

				}
			}
			return $result;
		}else{
			return array('status' => 'error');
		}
	}

	public function getOne($id){

		$query = $this->db->prepare('
			SELECT 	ID_PROYECTO,
					NOMBRE_PROYECTO,
					DESCRIPCION_PROYECTO,
					CREADOR,
					TELEFONO_CREADOR,
					EMAIL_CREADOR,
					FECHA_INGRESO,
					FECHA_IMPRESION,
					FECHA_ENTREGA,
					ESTADO
			FROM 	PROYECTO P
			WHERE 	ID_PROYECTO = :id
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
			INSERT INTO PROYECTO (	NOMBRE_PROYECTO,
									DESCRIPCION_PROYECTO,
									CREADOR,
									TELEFONO_CREADOR,
									EMAIL_CREADOR,
									FECHA_INGRESO	)
			VALUES 	( 	:nombre, 
						:descripcion, 
						:creador, 
						:telefono, 
						:email, 
						sysdate() 	) 
		');
		
		$query -> bindParam(':nombre', 		$data['nombre_proyecto']);
		$query -> bindParam(':descripcion', $data['descripcion']);
		$query -> bindParam(':creador', 	$data['creador']);
		$query -> bindParam(':telefono', 	$data['telefono']);
		$query -> bindParam(':email', 		$data['email']);

		if($query -> execute()){

			//Verificamos si se mandaron archivos adjuntos para almacenar
			$id = $this->db->lastInsertId();
			if($data['adjuntos']){
				foreach($data['adjuntos'] as $adjunto){
					$this->adjuntos->store($id, $adjunto);
				}
			}

			return array(
				'status' => 'success', 
				'id' => $id
			);
		}else{
			return array('status' => 'error');
		}
	}

	public function update($id, $data){
		$datos = array();
		$query = $this->db->prepare('	UPDATE 	PROYECTO 
										SET 	NOMBRE_PROYECTO 		= :nombre,
												DESCRIPCION_PROYECTO 	= :descripcion,
												CREADOR 				= :creador,
												TELEFONO_CREADOR 		= :telefono,
												EMAIL_CREADOR 			= :email
										WHERE 	ID_PROYECTO 			= :id');

		$query -> bindParam(':nombre', 		$data['nombre_proyecto']);
		$query -> bindParam(':descripcion', $data['descripcion']);
		$query -> bindParam(':creador', 	$data['creador']);
		$query -> bindParam(':telefono', 	$data['telefono']);
		$query -> bindParam(':email', 		$data['email']);
		$query -> bindParam(':id', 			$id);

		if($query -> execute()){
			return array(
				'status' => 'success', 
				'id' => $id
			);
		}else{
			return array('status' => 'error');
		}
	}

	public function updateStateToInProgress($id){
		$datos = array();
		$query = $this->db->prepare('	UPDATE 	PROYECTO 
										SET 	ESTADO = 2,
												FECHA_IMPRESION = sysdate()
										WHERE 	ID_PROYECTO = :id');

		$query -> bindParam(':id', $id);

		if($query -> execute()){
			return array(
				'status' => 'success', 
				'id' => $id
			);
		}else{
			return array('status' => 'error');
		}
	}

	public function updateStateToFinished($id){
		$datos = array();
		$query = $this->db->prepare('	UPDATE 	PROYECTO 
										SET 	ESTADO = 3,
												FECHA_ENTREGA = sysdate()
										WHERE 	ID_PROYECTO = :id');

		$query -> bindParam(':id', $id);

		if($query -> execute()){
			return array(
				'status' => 'success', 
				'id' => $id
			);
		}else{
			return array('status' => 'error');
		}
	}

	public function delete($id){
		$query = $this->db->prepare('	DELETE FROM PROYECTO
										WHERE ID_PROYECTO = :id ');
		
		$query -> bindParam(':id', 	$id);

		if($query -> execute()){
			return array('status' => 'success');
		}else{
			return array('status' => 'error');
		}
	}

		
}