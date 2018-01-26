<?php

class AdjuntoController{

	private $adjunto;

	public function __construct($db){
		$this->adjunto = new Adjunto($db);
	}

	public function delete($id){
		return $this->adjunto->delete($id);
	}

}