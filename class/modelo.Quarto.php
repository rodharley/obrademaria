<?php
class Quarto extends Persistencia{
	var $id = NULL;
	var $numero;
	var $capacidade;
	var $grupo = NULL;	
	
	public function recuperaTotal($grupo){
		$rs = $this->DAO_ExecutarQuery("select count(id) as total from ag_quarto where idGrupo = $grupo");	
		return $this->DAO_Result($rs,"total",0);
	}	
	
	public function incluir(){
		$oGrupo = new Grupo();
		$oGrupo->getById($oGrupo->md5_decrypt($_REQUEST['idGrupo']));
		$this->grupo = $oGrupo;
		$this->numero = $_REQUEST['numero'];
		$this->capacidade = $_REQUEST['capacidade'];
		$newid = $this->save();
		$_SESSION['tupi.mensagem'] = 29;	
		return $newid;
	}
	
	public function alterar(){
		$this->getById($_REQUEST['id']);
		$this->numero = $_REQUEST['numero'];
		$this->capacidade = $_REQUEST['capacidade'];
		$newid = $this->save();
		$_SESSION['tupi.mensagem'] = 30;	
		return $newid;
	}
	
	public function excluir(){
	$this->getById($this->md5_Decrypt($_REQUEST['idquarto']));		
		if($this->podeExcluir()){
			$this->delete($this->md5_Decrypt($_REQUEST['idquarto']));
			$_SESSION['tupi.mensagem'] = 31;
		}else{
			$_SESSION['tupi.mensagem'] = 32;
		}
	}
	
	public function podeExcluir(){
		$sql = "select id from ag_distribuicao where idQuarto = ".$this->id;
		$rs = $this->DAO_ExecutarQuery($sql);
		if($this->DAO_NumeroLinhas($rs) > 0)
		return false;
		else
		return true;
	}
	
}
?>