<?php 
include("tupi.inicializar.php"); 
$codTemplate = "relatorioHorizontal";
include("tupi.template.inicializar.php"); 
$codAcesso = 14;
include("tupi.seguranca.php");
$ogrupo = new Grupo();
$ogrupo->getById($ogrupo->md5_decrypt($_REQUEST['idGrupo']));
$tpl->COD_GRUPO = str_pad($ogrupo->id,7,"0", STR_PAD_LEFT);
$tpl->NOME_GRUPO = $ogrupo->nomePacote;
$tpl->ID_GRUPO_HASH = $_REQUEST['idGrupo'];
//recupera participantes aprovados
$opartic = new Participante();
$rs = $opartic->getRows(0,999,array("id"=>"asc"),array("grupo"=>"=".$ogrupo->id,"status"=>"!=".$opartic->STATUS_DESISTENTE()));
$cont = 1;
foreach($rs as $key => $p){
$tpl->ID = $cont;

$tpl->NOME = $p->nomeFamilia();
$tpl->DATA_NASCIMENTO = $opartic->convdata($p->cliente->dataNascimento,"mtn");
$tpl->LOCAL_NASCIMENTO =  $p->cliente->cidadeNascimento."-".$p->cliente->estadoNascimento;
$tpl->NACIONALIDADE = $p->cliente->nacionalidade;
$tpl->SEXO =  $p->cliente->sexo == "M" ? "MASC" : "FEM";
$tpl->PASSAPORTE =  $p->cliente->passaporte;
$tpl->DATA =  $opartic->convdata($p->cliente->dataEmissaoPassaporte,"mtn");
$tpl->ORGAO = $p->cliente->orgaoExpedidorPassaporte;
$tpl->VALIDADE =  $opartic->convdata($p->cliente->dataValidadePassaporte,"mtn");
$tpl->block("BLOCK_ITEM_LISTA");
$cont++;
}
if(!isset($_REQUEST['tupiSendEmail']))
$tpl->block("BLOCK_ENVIO_EMAIL");
include("tupi.template.finalizar.php"); 
?>