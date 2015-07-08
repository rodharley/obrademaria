<?php 
include("tupi.inicializar.php"); 
$codAcesso = 6;
include("tupi.seguranca.php");


//classes
$oPerfil = new Perfil();
//inclusao de perfil
if(isset($_REQUEST['acao'])){
	if($_REQUEST['acao'] == "Incluir"){
		$oPerfil->incluir();
	}
	if($_REQUEST['acao'] == "Alterar"){
		$oPerfil->alterar();
	}
	
	if($_REQUEST['acao'] == "Excluir"){
		$oPerfil->excluir();
	}
}else{
	$_SESSION['tupi.mensagem'] = 5;
}
header("Location:cadastro.perfis.php");
?>