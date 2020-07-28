<?php 
include("tupi.inicializar.php"); 
$codAcesso = 44;
include("tupi.seguranca.php");


//classes
$oConta = new Conta();
//inclusao de perfil
if(isset($_REQUEST['acao'])){
	if($_REQUEST['acao'] == "IncluirConta"){
		$oConta->incluir();
	}
	if($_REQUEST['acao'] == "AlterarConta"){
		$oConta->alterar();
	}
	
	if($_REQUEST['acao'] == "ExcluirConta"){
		$oConta->excluir();
	}
}else{
	$_SESSION['tupi.mensagem'] = 5;
}
header("Location:gestao.contas.php");
?>