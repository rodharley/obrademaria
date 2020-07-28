<?php 
include("tupi.inicializar.php"); 
$codTemplate = "relatorioHorizontal";
include("tupi.template.inicializar.php"); 
$codAcesso = 35;
include("tupi.seguranca.php");
//titulo do relatorio
$tpl->TITULO = "Relatório Por Período de Cheques Pré-datados";
$tpl->DATA_RELATORIO = "Data/Hora:" .date("d/m/Y h:i:s");
$oStatus = new StatusCheque();
$rsstatus = $oStatus->getRows();
$om = new Moeda();
$oC = new Cheque();
$dataRelatorio = $_REQUEST['dataInicio'] != "" ? $oC->convdata($_REQUEST['dataInicio'],"ntm") : "";
$dataFimRelatorio = $_REQUEST['dataFim'] != "" ? $oC->convdata($_REQUEST['dataFim'],"ntm") : "";
$rs = $oC->pesquisa($dataRelatorio,$dataFimRelatorio,$_REQUEST['numero'],$_REQUEST['status']);
$total = 0 ;
foreach($rs as $key => $cheque){
$tpl->ID_PARTICIPANTE = $cheque->pagamento->participante->id;
$tpl->NOME_PARTICIPANTE = 	$cheque->pagamento->participante->cliente->nomeCompleto;
$tpl->EMISSOR = $cheque->emissor->nomeCompleto;
$tpl->ID_CLIENTE_HASH = $om->md5_encrypt($cheque->emissor->id);
$tpl->GRUPO = $cheque->pagamento->participante->grupo->nomePacote;
$tpl->BANCO = $cheque->pagamento->banco->sigla;
$tpl->NUMERO = $cheque->numeroCheque;
$tpl->DATA = $oC->convData($cheque->dataCompensacao,"mtn");
$tpl->VALOR = $oC->money($cheque->valor,"atb");
$tpl->STATUS = $cheque->status->descricao;
$total += $cheque->valor;
$tpl->IDCHEQUE = $cheque->id;
foreach ($rsstatus as $key => $status){
$tpl->ID_STATUS = $status->id;
$tpl->LABEL_STATUS = $status->descricao;
$tpl->block("BLOCK_STATUS");	
}

$tpl->block("BLOCK_ITEM_LISTA");
}
$tpl->TOTAL = $oC->money($total,"atb");
$tpl->DATA_INICIO = $oC->convdata($dataRelatorio,"mtn");
$tpl->DATA_FIM = $oC->convdata($dataFimRelatorio,"mtn");
include("tupi.template.finalizar.php"); 
?>