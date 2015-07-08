<?php
class Usuario extends Persistencia{
	var $id = NULL;
	var $nome;
	var $senha;
	var $email;
	var $perfil = NULL;
	
	public function recuperaTotal(){
		$rs = $this->DAO_ExecutarQuery("select count(id) as total from ag_usuario");	
		return $this->DAO_Result($rs,"total",0);
	}
	
	public function alterar(){
		if($this->isEmailRepetido($_POST['email'],$_POST['id'])){
			$_SESSION['tupi.mensagem'] = 15;
		}else{
		$this->getById($_POST['id']);
		$this->nome = $_POST['nome'];
		$this->email = $_POST['email'];
		if($this->senha != $_POST['senha'])		
			$this->senha = md5($_POST['senha']);
		$oPerfil = new perfil();
		$oPerfil->getById($_REQUEST['perfil']);
		$this->perfil = $oPerfil;
		$this->save();
		$_SESSION['tupi.mensagem'] = 13;
		}
	}
	
	public function alterarDadosPessoais(){
		if($this->isEmailRepetido($_POST['email'],$_SESSION['ag_idUsuario'])){
			$_SESSION['tupi.mensagem'] = 15;
		}else{
		$this->getById($_SESSION['ag_idUsuario']);
		$this->nome = $_POST['nome'];
		$this->email = $_POST['email'];
		$this->save();
		$_SESSION['tupi.mensagem'] = 13;
		}
	}
	
	public function incluir(){
		if($this->isEmailRepetido($_POST['email'],0)){
			$_SESSION['tupi.mensagem'] = 15;
		}else{
		$this->nome = $_POST['nome'];
		$this->email = $_POST['email'];
		$this->senha = md5($_POST['senha']);
		$oPerfil = new perfil();
		$oPerfil->getById($_REQUEST['perfil']);
		$this->perfil = $oPerfil;
		$this->save();
		$_SESSION['tupi.mensagem'] = 12;	
		}
	}
	
	public function excluir(){		
		$this->delete($this->md5_Decrypt($_REQUEST['idUsuario']));
		$_SESSION['tupi.mensagem'] = 14;
	}
	
	
	public function isEmailRepetido($email,$idUsuario = 0){
		$sql = "select email from ag_usuario where email = '$email' and id != $idUsuario";
		$rs = $this->getSQL($sql);
		if (count($rs) > 0)
		return true;
		else
		return false;		
	}
	
	function getByEmail($email){
	$sql = "select * from ag_usuario where email = '".$email."'";
	$rs = $this->getSQL($sql);
	if (count($rs) > 0)
	$this->getById($rs[0]->id);
	return true;
	}

	function redefinirSenha(){
	$novaSenha = $this->makePassword(8);
	$mensagem = "Nova senha solicitada. A nova senha é : <strong>".$novaSenha."</strong>, guarde-a em um local seguro.";
	$email = @$this->mail_html($this->email,$this->REMETENTE, "Restauração de senha", $mensagem)	;
	if($email){
	$this->senha = md5($novaSenha);
	$this->save();
	return true;
	}else{
	return false;
	}
	}

	

}
?>
