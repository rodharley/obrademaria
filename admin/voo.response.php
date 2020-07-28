<?php 
include("tupi.inicializar.php"); 
$codAcesso = 11;
include("tupi.seguranca.php");


//classes
$oVoo = new Voo();

//inclusao de perfil
if(isset($_REQUEST['acao'])){
	if($_REQUEST['acao'] == "Incluir"){
		$oVoo->incluir();
	}
	
	if($_REQUEST['acao'] == "Excluir"){
		$oVoo->excluir();
	}
	if($_REQUEST['acao'] == "Alterar"){
		$oVoo->alterar();
	}
}else{
	$_SESSION['tupi.mensagem'] = 5;
}
header("Location:voo.lista.php?idGrupo=".$_REQUEST['idGrupo']);
?>