<?php
class Menu extends persistencia{
	var $id = NULL;
	var $descricao;
	var $url;
	var $menuPai = NULL;
	var $subMenus;
	
	function salvar($R){
		$this->descricao = $R['descricao'];
		if(strlen($R['iddepartamento']) > 0)
		$this->id = $R['iddepartamento'];
		if(strlen($R['departamentopai'])> 0){
			$this->departamentopai	= new departamento();
			$this->departamentopai->id = $R['departamentopai'];
		}
	$this->save();
	}
	
	function excluir($R){
		$this->delete($this->md5_decrypt($R['id']));
	}
	
	function getMenus(){
		return $this->getRows(0,999,array(),array("menuPai" => " is null"));
	}

}
?>