<?php
class LogParticipante extends Persistencia{
	var $id = NULL;
	var $participante = NULL;
	var $usuario = NULL;
	var $dataHora;
	var $valor;
	var $custo;
	
	
	function apagaLogsParticpante($idParticipante){
	$sql = "delete from ag_logparticipante where idParticipante = ".$idParticipante;
	$this->DAO_ExecutarQuery($sql);
	return true;
	}
}
?>