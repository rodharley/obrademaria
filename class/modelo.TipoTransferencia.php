<?php
class TipoTransferencia extends Persistencia{
	var $id = NULL;
	var $descricao;
	
	public function TRANSFERENCIA(){
		return 1;
	}
	public function DEPOSITO(){
		return 2;
	}
	public function TED(){
		return 3;
	}
	public function DOC(){
		return 4;
	}
	
}
?>