<?php include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 999;
include("tupi.seguranca.php");
$oUsuario = new Usuario();
$oUsuario->getById($_SESSION['ag_idUsuario']);	
$tpl->ACAO = "AlterarDadosPessoais";
$tpl->NOME = $oUsuario->nome;
$tpl->EMAIL = $oUsuario->email;
include("tupi.template.finalizar.php"); 
?>