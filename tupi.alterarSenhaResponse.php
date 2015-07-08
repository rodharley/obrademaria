<?php 
include("tupi.inicializar.php"); 
$codAcesso = 999;
include("tupi.seguranca.php");
$om = new mensagem();
$oU = new usuario();

if(isset($_REQUEST['acao'])){
	if($_REQUEST['acao'] == "Incluir"){
		$oUsuario->incluir();
	}
	if($_REQUEST['acao'] == "Alterar"){
		$oUsuario->alterar();
	}
	if($_REQUEST['acao'] == 'AlterarSenha'){
	$oU->getById($_SESSION['ag_idUsuario']);
	$oU->senha = md5(utf8_decode($_REQUEST['senha']));
	$oU->save();	
	$_SESSION['tupi.mensagem'] = 11;
	}
	if($_REQUEST['acao'] == 'AlterarDadosPessoais'){
		$oU->alterarDadosPessoais();		
	}
}else{
	$_SESSION['tupi.mensagem'] = 5;
}



	
	header("Location:home.php");
	exit();
?>