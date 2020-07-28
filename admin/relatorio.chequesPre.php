<?php
include("tupi.inicializar.php");
include("tupi.template.inicializar.php");
$codAcesso = 35;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '<ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="#">Relatórios</a> <span class="divider">/</span>
    </li>
    <li class="active">Relatório de Cheques Pré-datados</li>
    </ul>';
}

$oStatus = new StatusCheque();
$rs = $oStatus->getRows();
foreach ($rs as $key => $status){
$tpl->ID_STATUS = $status->id;
$tpl->LABEL_STATUS = $status->descricao;
$tpl->block("BLOCK_STATUS");	
}
include("tupi.template.finalizar.php");
?>