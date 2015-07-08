<?php
class Perfil extends Persistencia{
	var $id = NULL;
	var $descricao;
	
	public function recuperaTotal(){
		$rs = $this->DAO_ExecutarQuery("select count(id) as total from ag_perfil");	
		return $this->DAO_Result($rs,"total",0);
	}
	
	public function alterar(){
		$oAcesso = new Acesso();
		$this->getById($_POST['id']);
		$this->descricao = $_POST['descricao'];
		$this->save();
		$oAcesso->limparAcessos($_POST['id']);
		foreach ($_REQUEST['menus'] as $key => $valor){
		$oAc = new Acesso();
		$m = new Menu();
		$m->id = $valor;
		$oAc->menu = $m;
		$oAc->perfil = $this;
		$oAc->save();	
		}
		$_SESSION['tupi.mensagem'] = 7;
	}
	
	public function incluir(){
		$this->descricao = $_POST['descricao'];
		$this->save();
		foreach ($_REQUEST['menus'] as $key => $valor){
		$oAc = new Acesso();
		$m = new Menu();
		$m->id = $valor;
		$oAc->menu = $m;
		$oAc->perfil = $this;
		$oAc->save();	
		}
		$_SESSION['tupi.mensagem'] = 6;	
	}
	
	public function excluir(){
		$oAcesso = new Acesso();
		$idU = 	$this->md5_Decrypt($_REQUEST['idPerfil']);
		$olc = new usuario();
		$qtc = $olc->getNumRows(array("perfil"=>" = ".$idU));
		if($qtc == 0){
			$oAcesso->limparAcessos($idU);
			$this->delete($idU);
			$_SESSION['tupi.mensagem'] = 8;
		}else{
			$_SESSION['tupi.mensagem'] = 9;
		}	
	}
}
?>