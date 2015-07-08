<?php
class Moeda extends Persistencia{
	var $id = NULL;
	var $descricao;
	var $padrao;	
	var $cifrao;
	var $plural;
	
	public function DOLLAR(){
		return 1;
	}
	public function REAL(){
		return 2;
	}
}
?>