<?php
use \Firebase\JWT\JWT;

class Login{

	private $db;
	private $secret;

	public function __construct($db){
		$this->db = $db;
		$this->secret = 'Khao863a0s98dhna90a45s3';
	}

	public function doLogin($username, $password){
		$username = strtoupper($username);
		$query = $this->db->prepare('	SELECT UID, USERNAME, PASSWORD
    									FROM usuario WHERE USERNAME = :username');

		$query -> bindParam(':username', $username);

		if( $query -> execute() ){
			$user = $query->fetch();

			/* El usuario existe en la base de datos */
			if($user)
				return $this->verificarPassword($password, $user);
			else
				return array(
					'status' => 'error',
					'message' => 'usuario o password incorrectos'
				);
		}else
			return array(
				'status' => 'error',
				'message' => 'ocurrió un error inesperado'
			);
	}

	private function verificarPassword($password, $user){
		if(password_verify($password, $user['PASSWORD'])){

	        $usuario['iat'] = time();
	        $usuario['exp'] = time() + (12 * 60 * 60);
	        $usuario['usr'] = $user['USERNAME'];
	        $usuario['uid'] = $user['UID'];

			$jwt = JWT::encode($usuario, $this->secret);

			// guardar token en la bd
			$query = $this->db->prepare('UPDATE usuario SET TOKEN = :token
										 WHERE UID = :uid');

			$query->bindParam(':token', $jwt, PDO::PARAM_STR);
			$query->bindParam(':uid', $user['UID'], PDO::PARAM_STR);

			if( $query->execute() )
				return array(
					"status" => "success",
					"message" => "bienvenido",
					"token" => $jwt
				);
			else
				return array(
					'status' => 'error',
					'message' => 'ocurrió un error al generar el token'
				);
		}else
			return array(
				'status' => 'error',
				'message' => 'usuario o password incorrectos'
			);
	}
		
}