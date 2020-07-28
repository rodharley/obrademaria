<?php 
include("tupi.inicializar.php"); 
$codTemplate = "relatorio";
include("tupi.template.inicializar.php"); 
$codAcesso = 39;
include("tupi.seguranca.php");
//titulo do relatorio
$tpl->TITULO = "Relatório de Inscrições por Período";
$tpl->DATA_RELATORIO = "Data/Hora:" .date("d/m/Y h:i:s");
//declara as classes
$ol = new Participante();
$om = new Moeda();
$op = new Pagamento();
$dataRelatorio = $ol->convdata($_REQUEST['dataInicio'],"ntm");
$dataFimRelatorio = $ol->convdata($_REQUEST['dataFim'],"ntm");
$rsLogs = $ol->participantesPeriodo($dataRelatorio,$dataFimRelatorio);
$total  = 0;
$totalReal = 0;
foreach($rsLogs as $key => $log){
$rsPag = $op->primeiroPagamentoCliente($log->id);	
if (count($rsPag) > 0 ){
$pag = $rsPag[0];
$tpl->PARTICIPANTE = $log->cliente->nomeCompleto;
$tpl->DATA = $log->convdata($log->dataInscricao,"mtn");
$tpl->GRUPO = $log->grupo->nomePacote;
if($log->grupo->moeda->id == $om->DOLLAR()){
	if($log->pacoteOpcional == 1) {
	$tpl->VALOR_DOLLAR = $log->grupo->moeda->cifrao." ".$log->money($log->grupo->valorAdesao+$log->grupo->valorAdesaoOpcional,"atb");
	$tpl->VALOR_REAL = "R$ ".$log->money((($log->grupo->valorAdesao+$log->grupo->valorAdesaoOpcional)*$pag->cotacaoReal),"atb");	
	$total += $log->grupo->valorAdesao+$log->grupo->valorAdesaoOpcional;
	$totalReal += (($log->grupo->valorAdesao+$log->grupo->valorAdesaoOpcional)*$pag->cotacaoReal);
	}else{
	$tpl->VALOR_DOLLAR = $log->grupo->moeda->cifrao." ".$log->money($log->grupo->valorAdesao,"atb");
	$tpl->VALOR_REAL = "R$ ".$log->money(($log->grupo->valorAdesao*$pag->cotacaoReal),"atb");
	$total += $log->grupo->valorAdesao;
	$totalReal += ($log->grupo->valorAdesao*$pag->cotacaoReal);
	}
$tpl->CAMBIO = $log->participante->grupo->moeda->cifrao." ".$log->money($pag->cotacaoReal,"atb");

}else{
	if($log->pacoteOpcional == 1) {
	$tpl->VALOR_DOLLAR = "-";
	$tpl->VALOR_REAL = $log->grupo->moeda->cifrao." ".$log->money($log->grupo->valorAdesao+$log->grupo->valorAdesaoOpcional,"atb");
	$totalReal += $log->grupo->valorAdesao+$log->grupo->valorAdesaoOpcional;	
	}else{
	$tpl->VALOR_DOLLAR = "-";
	$tpl->VALOR_REAL = $log->grupo->moeda->cifrao." ".$log->money($log->grupo->valorAdesao,"atb");
	$totalReal += $log->grupo->valorAdesao;
	}
$tpl->CAMBIO = "-";
}
$tpl->block("BLOCK_ITEM_LISTA");
}
}//fim do loop de grupos
$tpl->TOTAL = $ol->money($total,"atb");
$tpl->TOTALREAL = $ol->money($totalReal,"atb");
$tpl->DATA_INICIO = $ol->convdata($dataRelatorio,"mtn");
$tpl->DATA_FIM = $ol->convdata($dataFimRelatorio,"mtn");
include("tupi.template.finalizar.php"); 
?>