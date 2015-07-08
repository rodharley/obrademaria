<?php
class TipoPagamento extends Persistencia{
	var $id = NULL;
	var $descricao;
	


	public function DINHEIRO(){
		return 1;
	}
	public function CARTAO(){
		return 2;
	}
	
	public function CHEQUE(){
		return 3;
	}
	public function BANCO(){
		return 4;
	}
	public function CREDITO(){
		return 5;
	}
	public function CARNE(){
		return 6;
	}
	public function DEBITO(){
		return 7;
	}
}

?>