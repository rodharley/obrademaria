<?php 
include("tupi.inicializar.php"); 
$codTemplate = "relatorio";
include("tupi.template.inicializar.php"); 
$codAcesso = 20;
include("tupi.seguranca.php");
$tpl->TITULO = "Lista de Camisetas";
$tpl->DATA_RELATORIO = "Data/Hora:" .date("d/m/Y h:i:s");
$ogrupo = new Grupo();
$ogrupo->getById($ogrupo->md5_decrypt($_REQUEST['idGrupo']));
$tpl->COD_GRUPO = str_pad($ogrupo->id,7,"0", STR_PAD_LEFT);
$tpl->NOME_GRUPO = $ogrupo->nomePacote;
$tpl->ID_GRUPO_HASH = $_REQUEST['idGrupo'];
//recupera participantes aprovados
$opartic = new Participante();
$rs = $opartic->getRows(0,999,array("id"=>"asc"),array("grupo"=>"=".$ogrupo->id,"status"=>"!=".$opartic->STATUS_DESISTENTE()));
$rsTotaisCamisetas = $opartic->recuperaTotaisCamisetas($ogrupo->id);
$cont = 1;
foreach($rs as $key => $p){
$tpl->NUMERO = $cont;
$tpl->CAMISETA = $p->cliente->tamanhoCamisa;
$tpl->NOME = $p->cliente->nomeCompleto;
$tpl->block("BLOCK_ITEM_LISTA");
$cont++;
}
while($row = $opartic->DAO_GerarArray($rsTotaisCamisetas)){
$tpl->TOTAL_CAMISA = $row['tamanhoCamisa']	;
$tpl->TOTAL = $row['total'];
$tpl->block("BLOCK_COLUNA_TOTAL");
}
if(!isset($_REQUEST['tupiSendEmail']))
$tpl->block("BLOCK_ENVIO_EMAIL");
include("tupi.template.finalizar.php"); 
?>