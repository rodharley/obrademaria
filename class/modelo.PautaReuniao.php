<?php
class PautaReuniao extends Persistencia{
	var $id = NULL;
	var $pauta;
	var $grupo = NULL;	
	
	function getByGrupo($idGrupo){
	$rs = $this->getRows(0,1,array(),array("grupo"=>"=".$idGrupo));
	if(count($rs) > 0){
		$this->getById($rs[0]->id);
		return true;
	}else{
		return false;
		}
	}
	
	
	public function salvar(){
		if($_POST['id'] != ""){
		$this->getById($_POST['id']);
		$this->pauta = $_POST['pauta'];
		$this->save();
		$_SESSION['tupi.mensagem'] = 33;	
		}else{
		$oG = new Grupo();
		$oG->id = $this->md5_decrypt($_REQUEST['idGrupo']);
		$this->grupo = $oG;
		$this->pauta = $_POST['pauta'];
		$this->save();
		$_SESSION['tupi.mensagem'] = 33;
		}
	}
}
?>