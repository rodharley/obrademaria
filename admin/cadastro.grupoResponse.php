<?php 
include("tupi.inicializar.php"); 
$codAcesso = 8;
include("tupi.seguranca.php");


//classes
$oGrupo = new Grupo();
$oMoeda = new Moeda();
$oStatusGrupo = new StatusGrupo();
//inclusao de perfil
if(isset($_REQUEST['acao'])){
	if($_REQUEST['acao'] == "Incluir"){
		$oGrupo->incluir();
	}
	if($_REQUEST['acao'] == "Alterar"){
		$oGrupo->alterar();
	}
	
	if($_REQUEST['acao'] == "Excluir"){
		$oGrupo->excluir();
	}
}else{
	$_SESSION['tupi.mensagem'] = 5;
}
header("Location:cadastro.grupos.php");
?>