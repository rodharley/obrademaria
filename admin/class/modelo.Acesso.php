<?php
class Acesso extends Persistencia{
	var $id = NULL;
	var $menu = NULL;
	var $perfil = NULL;
	
	
	function limparAcessos($idPerfil){
	$sql = "delete from ag_menuperfil where idPerfil = ".$idPerfil;
	$this->DAO_ExecutarQuery($sql);
	return true; 	
	}
	
	
}
?>