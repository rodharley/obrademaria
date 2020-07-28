<?php
class LogUsuario extends Persistencia{
	var $id = NULL;
	var $usuario = NULL;
	var $data;
	var $movimento;

	
	function apagaLogsUsuario($idUsuario){
	$sql = "delete from ag_logusuario where idUsuario = ".$idUsuario;
	$this->DAO_ExecutarQuery($sql);
	return true;
	}
	public function logsPeriodo($datai,$dataf,$user){
	$sql = "select * from ag_logusuario where data between '".$datai."' and '".$dataf."' ";
	
	if ($user != "") {
	$sql .= " and idUsuario = ".$user;
	}
	return $this->getSQL($sql);
	}
}
?>