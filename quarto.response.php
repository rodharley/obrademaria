<?php 
include("tupi.inicializar.php"); 
$codAcesso = 11;
include("tupi.seguranca.php");


//classes
$oQuarto = new Quarto();

//inclusao de perfil
if(isset($_REQUEST['acao'])){
	if($_REQUEST['acao'] == "Incluir"){
		$oQuarto->incluir();
	}
	
	if($_REQUEST['acao'] == "Excluir"){
		$oQuarto->excluir();
	}
	if($_REQUEST['acao'] == "Alterar"){
		$oQuarto->alterar();
	}
}else{
	$_SESSION['tupi.mensagem'] = 5;
}
header("Location:quarto.lista.php?idGrupo=".$_REQUEST['idGrupo']);
?>