<?php 
include("tupi.inicializar.php"); 
$codTemplate = "relatorio";
include("tupi.template.inicializar.php"); 
$codAcesso = 24;
include("tupi.seguranca.php");
//titulo do relatorio
$tpl->TITULO = "Relatório de Contas a Pagar";
$tpl->DATA_RELATORIO = "Data/Hora:" .date("d/m/Y h:i:s");
$oC = new Conta();
$oCR = new ContaRealizado();
$dataRelatorio = $_REQUEST['ano']."-".$_REQUEST['mes']."-01";
$dataFimRelatorio = $oCR->ultimoDiaMes($dataRelatorio);
$filtro = array("dataPagamento"=> "between '".$dataRelatorio."' and '".$dataFimRelatorio."'");
$rs = $oCR->getRows(0,9999,array("dataPagamento"=>"ASC"),$filtro);
//$tpl->DATA_ATUAL = date("d/m/Y");
$total = 0;
foreach($rs as $key => $c){
$tpl->DESCRICAO = $c->conta->descricao;
$tpl->PARCELA = $c->parcela."/".$c->conta->parcelas;
$tpl->VALOR = $oCR->money($c->valorPagamento,"atb");
$tpl->DATA = $oCR->convdata($c->dataPagamento,"mtn");
$total += $c->valorPagamento;
$tpl->block("BLOCK_ITEM_LISTA");	
}
$tpl->TOTAL = $oCR->money($total,"atb");
$tpl->DATA_INICIO = $oCR->convdata($dataRelatorio,"mtn");
$tpl->DATA_FIM = $oCR->convdata($dataFimRelatorio,"mtn");


include("tupi.template.finalizar.php"); 
?>