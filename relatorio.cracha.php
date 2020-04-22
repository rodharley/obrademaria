<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 19;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '<ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="#">Relatórios</a> <span class="divider">/</span>
    </li>
    <li class="active">Crachás de Participantes</li>
    </ul>';
}
$oGrupo = new Grupo();
$rsAnos = $oGrupo->recuperaAnos();
//$rsGrupos = $oGrupo->getRows(0,999,array("ano"=>"desc"),array());	

while($row = $oGrupo->DAO_GerarArray($rsAnos)){
	$tpl->ID_ANO = $row['ano'];
	$tpl->LABEL_ANO = $row['ano'];	
	$tpl->block("BLOCK_ANO");	
}
include("tupi.template.finalizar.php"); 
?>