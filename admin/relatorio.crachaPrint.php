<?php 
include("tupi.inicializar.php"); 
$codTemplate = 'cracha';
include("tupi.template.inicializar.php"); 
$codAcesso = 19;
include("tupi.seguranca.php");
//titulo do relatorio
$tpl->TITULO = "Crachá de Participantes";
$opartic = new Participante();
$rs = $opartic->getRows(0,999,array("id"=>"asc"),array("grupo"=>"=".$opartic->md5_decrypt($_REQUEST['idGrupo']),"status"=>"!=".$opartic->STATUS_DESISTENTE()));
$contEtiqueta = 0;
$maximoFolha = false;
foreach($rs as $key => $p){
$tpl->NOME_CRACHA = $p->cliente->nomeCracha;
$tpl->block("BLOCK_CRACHA");
$contEtiqueta ++;
if($contEtiqueta == 9){
$contEtiqueta = 0;
$maximoFolha = true;
$tpl->block("BLOCK_FOLHA");
}
}
if(!$maximoFolha)
$tpl->block("BLOCK_FOLHA");
include("tupi.template.finalizar.php"); 
?>