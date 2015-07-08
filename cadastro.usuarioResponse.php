<?php 
include("tupi.inicializar.php"); 
$codAcesso = 7;
include("tupi.seguranca.php");


//classes
$oUsuario = new Usuario();
//inclusao de perfil
if(isset($_REQUEST['acao'])){
	if($_REQUEST['acao'] == "Incluir"){
		$oUsuario->incluir();
	}
	if($_REQUEST['acao'] == "Alterar"){
		$oUsuario->alterar();
	}
	
	if($_REQUEST['acao'] == "Excluir"){
		$oUsuario->excluir();
	}
	
}else{
	$_SESSION['tupi.mensagem'] = 5;
}
header("Location:cadastro.usuarios.php");
?>