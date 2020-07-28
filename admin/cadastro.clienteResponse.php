<?php 
include("tupi.inicializar.php"); 
$codAcesso = 5;
include("tupi.seguranca.php");


//classes
$oCliente = new Cliente();
//inclusao de perfil
if(isset($_REQUEST['acao'])){
	if($_REQUEST['acao'] == "Incluir"){
		$oCliente->incluir();
	}
	if($_REQUEST['acao'] == "Alterar"){
		$oCliente->alterar();
	}
	
	if($_REQUEST['acao'] == "Excluir"){
		$oCliente->excluir();
	}	

}else{
	$_SESSION['tupi.mensagem'] = 5;
}
header("Location:cadastro.clientes.php");
?>