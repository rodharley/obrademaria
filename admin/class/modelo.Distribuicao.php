<?php
class Distribuicao extends Persistencia{
	var $id = NULL;
	var $quarto;
	var $participante;

	
	public function remover($idP){
		$sql = "delete from ag_distribuicao where idParticipante = $idP";
		$this->DAO_ExecutarQuery($sql);
		return true;
	}

}
?>