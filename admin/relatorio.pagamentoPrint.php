<?php 
include("tupi.inicializar.php"); 
$codTemplate = "relatorio";
include("tupi.template.inicializar.php"); 
$codAcesso = 16;
include("tupi.seguranca.php");
//titulo do relatorio
$tpl->TITULO = "Relatório de Pagamentos de Participantes";
$tpl->DATA_RELATORIO = "Data/Hora:" .date("d/m/Y h:i:s");
$ogrupo = new Grupo();
$om = new Moeda();
$oAbat = new Abatimento();
$ogrupo->getById($ogrupo->md5_decrypt($_REQUEST['idGrupo']));
$tpl->COD_GRUPO = str_pad($ogrupo->id,7,"0", STR_PAD_LEFT);
$tpl->NOME_GRUPO = $ogrupo->nomePacote;
$tpl->VALOR_GRUPO = $ogrupo->money($ogrupo->getValorTotal(0),"atb");
$tpl->CIFRAO_GRUPO = $ogrupo->moeda->cifrao;
$tpl->MOEDA_GRUPO_PLURAL = $ogrupo->moeda->plural;
//$tpl->DATA_ATUAL = date("d/m/Y");
//pacote opcional
if($ogrupo->possuiPacoteOpcional == 1){
$tpl->NOME_GRUPO_OPCIONAL = $ogrupo->nomePacoteOpcional;
$tpl->VALOR_GRUPO_OPCIONAL = $ogrupo->money($ogrupo->getValorTotalOpcional(),"atb");
$tpl->block("BLOCK_OPCIONAL");
}
if($ogrupo->moeda->id == $om->DOLLAR()){
$tpl->block("BLOCK_GRUPO_DOLLAR_HEAD");
}
//recupera participantes aprovados
$opartic = new Participante();
$rs = $opartic->getRows(0,999,array("id"=>"asc"),array("grupo"=>"=".$ogrupo->id,"status"=>"in(".$opartic->STATUS_PENDENTE().",".$opartic->STATUS_APROVADO().")"));
$cont = 1;
$totalGeralPagoReal = 0;
$totalGeralPagoDollar = 0;
$totalGeralPendente = 0;
foreach($rs as $key => $p){
$totalDollar = 0;
$totalReal = 0;
$tpl->ID = $cont;
$tpl->NOME_PARTICIPANTE = $p->cliente->nomeCompleto;
$tpl->STATUS = $p->status->descricao;
$rsabats = $oAbat->getRows(0,999,array(),array("participante"=>"=".$p->id));
	foreach($rsabats as $keyA => $abat){
		$totalDollar += $abat->getValorDollar();
		$totalReal += $abat->getValorReal();
	}
$tpl->VALOR_PAGO_REAL = $om->money($totalReal,"atb");
$tpl->VALOR_PAGO_DOLLAR = $om->money($totalDollar,"atb");
$totalGeralPagoReal += $totalReal;
$totalGeralPagoDollar += $totalDollar;
if($ogrupo->moeda->id == $om->DOLLAR()){
$tpl->VALOR_PENDENTE = $om->money($p->valorTotal - $totalDollar,"atb");
$totalGeralPendente += $p->valorTotal - $totalDollar;
$tpl->block("BLOCK_GRUPO_DOLLAR");
}else{
$tpl->VALOR_PENDENTE = $om->money($p->valorTotal - $totalReal,"atb");
$totalGeralPendente += $p->valorTotal - $totalReal;
}
$tpl->block("BLOCK_ITEM_LISTA");
$cont++;
}
if($ogrupo->moeda->id == $om->DOLLAR()){
$tpl->block("BLOCK_TOTAL_GRUPO_DOLLAR");
}

$tpl->TOTAL_VALOR_PAGO_DOLLAR = $om->money($totalGeralPagoDollar,"atb");
$tpl->TOTAL_VALOR_PAGO_REAL = $om->money($totalGeralPagoReal,"atb");
$tpl->TOTAL_VALOR_PENDENTE = $om->money($totalGeralPendente,"atb");
include("tupi.template.finalizar.php"); 
?>