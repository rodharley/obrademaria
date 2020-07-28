<?php
class Voo extends Persistencia{
	var $id = NULL;
	var $companhiaAerea = NULL;
	var $grupo =  NULL;
	var $dataEmbarque;
	var $horaEmbarque;
	var $horaChegada;
	var $trecho;
	var $numeroVoo;
	
	public function recuperaTotal($grupo){
		$rs = $this->DAO_ExecutarQuery("select count(id) as total from ag_voo where idGrupo = $grupo");	
		return $this->DAO_Result($rs,"total",0);
	}	
	
	public function incluir(){
		$oGrupo = new Grupo();
		$oGrupo->getById($oGrupo->md5_decrypt($_REQUEST['idGrupo']));
		$oCA = new CompanhiaAerea();
		$oCA->id = $_REQUEST['companhiaAerea'];
		
		$this->companhiaAerea = $oCA;
		$this->grupo = $oGrupo;
		$this->dataEmbarque = $this->convdata($_REQUEST['dataEmbarque'],"ntm");
		$this->horaEmbarque = $_REQUEST['horaEmbarque'];
		$this->horaChegada = $_REQUEST['horaChegada'];
		$this->trecho = $_REQUEST['trecho'];
		$this->numeroVoo = $_REQUEST['numeroVoo'];
		$newid = $this->save();
		$_SESSION['tupi.mensagem'] = 26;	
		return $newid;
	}
	
	public function alterar(){
		$this->getById($_REQUEST['id']);
		$oCA = new CompanhiaAerea();
		$oCA->id = $_REQUEST['companhiaAerea'];
		$this->trecho = $_REQUEST['trecho'];
		$this->companhiaAerea = $oCA;
		$this->dataEmbarque = $this->convdata($_REQUEST['dataEmbarque'],"ntm");
		$this->horaEmbarque = $_REQUEST['horaEmbarque'];
		$this->horaChegada = $_REQUEST['horaChegada'];
		$this->numeroVoo = $_REQUEST['numeroVoo'];
		$newid = $this->save();
		$_SESSION['tupi.mensagem'] = 27;	
		return $newid;
	}
	
	public function excluir(){		
		$this->delete($this->md5_Decrypt($_REQUEST['idVoo']));
		$_SESSION['tupi.mensagem'] = 28;
	}
}
?>