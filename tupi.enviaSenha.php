<?
include("tupi.inicializar.php");
//classes
$oUsuario = new Usuario();
$omensagem = new Mensagem();
$oUsuario->getByEmail($_REQUEST['usuario']);
	if($oUsuario->id == NULL){
		$_SESSION['tupi.mensagem'] = 3;
		header("Location:index.php");
		exit();
	}else{
	$oUsuario->redefinirSenha();
		$_SESSION['tupi.mensagem'] = 10;
		header("Location:index.php");
		exit();
	}
?>