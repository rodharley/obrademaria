<?php 
include("tupi.inicializar.php"); 
$codTemplate = "relatorioHorizontal";
include("tupi.template.inicializar.php"); 
$codAcesso = 18;
include("tupi.seguranca.php");
$ogrupo = new Grupo();
$ogrupo->getById($ogrupo->md5_decrypt($_REQUEST['idGrupo']));
$tpl->COD_GRUPO = str_pad($ogrupo->id,7,"0", STR_PAD_LEFT);
$tpl->NOME_GRUPO = $ogrupo->nomePacote;
$tpl->ID_GRUPO_HASH = $_REQUEST['idGrupo'];
$tpl->DATA_RELATORIO = "Data/Hora:" .date("d/m/Y h:i:s");
//recupera participantes aprovados
$opartic = new Participante();
$rs = $opartic->relatListaParticipante($ogrupo->id,$opartic->STATUS_DESISTENTE());
$cont = 1;
foreach($rs as $key => $p){
$tpl->NUMERO = $cont;

$tpl->NOME = $p->cliente->nomeCompleto;
$tpl->TELEFONE = $p->cliente->telefoneResidencial;
$tpl->TELEFONE2 = $p->cliente->telefoneComercial;
$tpl->CELULAR = $p->cliente->celular;
$celular;
$tsdata = strtotime($p->cliente->dataNascimento);
$tpl->ANIVERSARIO = date("d/m",$tsdata);
$tpl->EMAIL =  $p->cliente->email;
$tpl->block("BLOCK_ITEM_LISTA");
$cont++;
}
if(!isset($_REQUEST['tupiSendEmail']))
$tpl->block("BLOCK_ENVIO_EMAIL");

include("tupi.template.finalizar.php"); 
?>