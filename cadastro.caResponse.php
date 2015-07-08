<?php 
include("tupi.inicializar.php"); 
$codAcesso = 46;
include("tupi.seguranca.php");


//classes
$oCA = new CompanhiaAerea();
//inclusao de perfil
if(isset($_REQUEST['acao'])){
	if($_REQUEST['acao'] == "Incluir"){
		$oCA->incluir();
	}
	if($_REQUEST['acao'] == "Alterar"){
		$oCA->alterar();
	}
	
	if($_REQUEST['acao'] == "Excluir"){
		$oCA->excluir();
	}
	
}else{
	$_SESSION['tupi.mensagem'] = 5;
}
header("Location:cadastro.ca.php");
?>