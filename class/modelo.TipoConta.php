<?php
class TipoConta extends Persistencia{
	var $id = NULL;
	var $descricao;
	
	public function UNICA(){
		return 1;
	}
	public function PERIODICA(){
		return 2;
	}
	public function PERIODO(){
		return 3;
	}
}
?>