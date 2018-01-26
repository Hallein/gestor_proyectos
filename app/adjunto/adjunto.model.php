<?php

class Adjunto{

	private $db;

	public function __construct($db){
		$this->db = $db;
	}

	public function getAllById($id){

		$query = $this->db->prepare('	
				SELECT 	ID_ADJUNTO,
						NOMBRE_ADJUNTO,
						DESCRIPCION_ADJUNTO,
						EXTENSION,
						FECHA_ADJUNTO
				FROM 	ADJUNTO A
				WHERE 	ID_PROYECTO = :id
				ORDER BY 	ID_ADJUNTO ASC
		');

		$query -> bindParam(':id', $id);

		if($query->execute()){
			return $query->fetchAll();
		}else{
			return array('status' => 'error');
		}
	}

	public function store($id_proyecto, $data){
		$datos = array();
		$query = $this->db->prepare(' 	
			INSERT INTO ADJUNTO (	ID_PROYECTO,
									NOMBRE_ADJUNTO,
									DESCRIPCION_ADJUNTO,
									EXTENSION,
									FECHA_ADJUNTO	)
			VALUES 	( 	:id,
						:nombre, 
						:descripcion, 
						:extension,
						sysdate() 	) 
		');
		
		$query -> bindParam(':id', 			$id_proyecto);
		$query -> bindParam(':nombre', 		$data['nombre_adjunto']);
		$query -> bindParam(':descripcion', $data['descripcion']);
		$query -> bindParam(':extension', 	$data['extension']);

		if($query -> execute()){

			//Renombrar el archivo de acuerdo a un numero random rand(0, 1000)
			$destino = FILES . $data['archivo']["name"] . '-' . rand(0, 1000);
			if (move_uploaded_file($data['archivo']["tmp_name"], $destino)) {
		        return array(
					'status' => 'success', 
					'id' => $this->db->lastInsertId(),
					'uploaded' => true
				);
		    } else {
		        return array(
					'status' => 'error', 
					'uploaded' => false,
					'filename' => $data['archivo']["name"]
				);
		    }

		}else{
			return array('status' => 'error');
		}
	}

	public function delete($id){
		$query = $this->db->prepare('	DELETE FROM ADJUNTO
										WHERE ID_ADJUNTO = :id ');
		
		$query -> bindParam(':id', 	$id);

		if($query -> execute()){
			return array('status' => 'success');
		}else{
			return array('status' => 'error');
		}
	}

		
}