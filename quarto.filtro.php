<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 12;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '<ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="grupos.andamento.php">Grupos</a> <span class="divider">/</span>
    </li>
    <li class="active">Seleciona Grupo</li>
    </ul>';
}
$oGrupo = new Grupo();
$rsGrupos = $oGrupo->getRows(0,999,array(),array("status"=>"=".$oGrupo->STATUS_ANDAMENTO()));	

foreach($rsGrupos as $key => $Grupo){
	$tpl->ID_GRUPO = $oGrupo->md5_encrypt($Grupo->id);
	$tpl->LABEL_GRUPO = $Grupo->nomePacote;	
$tpl->block("BLOCK_GRUPO");	
}
include("tupi.template.finalizar.php"); 
?>