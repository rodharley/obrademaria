<?php
include("tupi.inicializar.php");
include("tupi.template.inicializar.php");
$codAcesso = 25;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '<ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="#">Relatórios</a> <span class="divider">/</span>
    </li>
    <li class="active">Relatório para Mala Direta</li>
    </ul>';
}
$oEstCivil = new EstadoCivil();
$rsec = $oEstCivil->getRows();
$oGrupo = new Grupo();
$rsAnos = $oGrupo->recuperaAnos();
//estado civil
foreach($rsec as $key => $ec){
$tpl->ID_ESTCIVIL = $ec->id;
$tpl->LABEL_ESTCIVIL = $ec->descricao;	
$tpl->block("BLOCK_ESTADOCIVIL");
}

while($row = $oGrupo->DAO_GerarArray($rsAnos)){
	$tpl->ID_ANO = $row['ano'];
	$tpl->LABEL_ANO = $row['ano'];	
	$tpl->block("BLOCK_ANO");	
}

include("tupi.template.finalizar.php");
?>