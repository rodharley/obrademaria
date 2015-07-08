<?php
include("tupi.inicializar.php");
include("tupi.template.inicializar.php");
$codAcesso = 24;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '<ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="#">Relatórios</a> <span class="divider">/</span>
    </li>
    <li class="active">Relatório de Contas a Pagar</li>
    </ul>';
}


for($i = date("Y"); $i >= 2000 ; $i--){
	$tpl->ANO = $i;
$tpl->block("BLOCK_ANO");
}
include("tupi.template.finalizar.php");
?>