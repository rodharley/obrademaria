<?php
include("tupi.inicializar.php");
include("tupi.template.inicializar.php");
$codAcesso = 27;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '<ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="#">Relatórios</a> <span class="divider">/</span>
    </li>
    <li class="active">Relatório de Log de Usuários</li>
    </ul>';
}
$oUser  = new Usuario();
$rsUser = $oUser->getRows(0,999,array("nome"=>"asc"),array());	

foreach($rsUser as $key => $user){
	$tpl->ID_USER = $user->id;
	$tpl->LABEL_USER = $user->nome;
	$tpl->block("BLOCK_USER");	
}

include("tupi.template.finalizar.php");
?>