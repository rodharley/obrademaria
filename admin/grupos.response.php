<?php 
include("tupi.inicializar.php"); 
$codAcesso = 10;
include("tupi.seguranca.php");


//classes
$oP = new PautaReuniao();
$oG = new Grupo();
//inclusao de perfil
if(isset($_REQUEST['acao'])){
	if($_REQUEST['acao'] == "SalvaPauta"){
		$oP->salvar();
	}	
	
	if($_REQUEST['acao'] == "migrar"){
		$oG->migrarParticipantes();
	}	
}else{
	$_SESSION['tupi.mensagem'] = 5;
}
header("Location:grupos.andamento.php");
?>