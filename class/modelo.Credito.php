<?php
class Credito extends Persistencia{
	var $id = NULL;
	var $cliente = NULL;
	var $valor;	
	var $moeda = NULL;
	var $obs;
	var $data;	
	var $bitUtilizado;
	var $participante = NULL;
	var $cotacaoReal;
	
	function CALCULA_REAL(){
	$moeda = new Moeda();
	$valorCalculo = $this->valor;
	if($this->moeda->id == $moeda->REAL())
		return $valorCalculo;
	else
		return $valorCalculo * $this->cotacaoReal;
		
}

function CALCULA_DOLLAR(){
	$moeda = new Moeda();
		$valorCalculo = $this->valor;
 	if($this->moeda->id == $moeda->DOLLAR())
		return $valorCalculo;
		else 
		return $valorCalculo / $this->cotacaoReal;		
}
}
?>