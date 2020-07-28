<?php 
include("tupi.inicializar.php"); 
$codTemplate = "relatorio";
include("tupi.template.inicializar.php"); 
$codAcesso = 34;
include("tupi.seguranca.php");
//titulo do relatorio
$tpl->TITULO = "Relatório Mensal de Forma de Pagamento";
$tpl->DATA_RELATORIO = "Data/Hora:" .date("d/m/Y h:i:s");
$oC = new Cheque();
$om = new Moeda();
$oP = new Pagamento();
$oTP = new TipoPagamento();
$oTT = new TipoTransferencia();
$dataRelatorio = $_REQUEST['ano']."-".$_REQUEST['mes']."-01";
$dataFimRelatorio = $oP->ultimoDiaMes($dataRelatorio);
$filtro = array("dataPagamento"=> "between '".$dataRelatorio."' and '".$dataFimRelatorio."'","cancelado"=>"=0");
$rs = $oP->getRows(0,9999,array(),$filtro);
$rsCheques = $oC->pesquisa($dataRelatorio,$dataFimRelatorio,"",0);
//$tpl->DATA_ATUAL = date("d/m/Y");
$valorEspecie = 0;
$valorCartao = 0;
$valorCheque = 0;
$valorTED = 0;
$valorDOC = 0;
$valorTransf = 0;
$valorDeposito = 0;
$valorDebito = 0;
//dollar
$valorEspecied = 0;
$valorCartaod = 0;
$valorChequed = 0;
$valorTEDd = 0;
$valorDOCd = 0;
$valorTransfd = 0;
$valorDepositod = 0;
$valorDebitod = 0;
//somatorio dos cheques
foreach($rsCheques as $key => $cheque){
$valorChequed += @($cheque->valor/$cheque->pagamento->cotacaoReal);
$valorCheque += @$cheque->valor;
}
//somatorio dos outros pagamentos
foreach($rs as $key => $p){
	switch($p->tipo->id){
		case $oTP->DINHEIRO():
		$valorEspecied += @$p->CALCULA_DOLLAR();
		$valorEspecie += $p->CALCULA_REAL();
		break;
		case $oTP->CARTAO():
		$valorCartaod += @$p->CALCULA_DOLLAR();
		$valorCartao += @$p->CALCULA_REAL();
		break;
		case $oTP->DEBITO():
		$valorDebitod += @$p->CALCULA_DOLLAR();
		$valorDebito += @$p->CALCULA_REAL();
		break;
		case $oTP->CHEQUE():
		//$valorChequed += $p->CALCULA_DOLLAR();
		//$valorCheque += $p->CALCULA_REAL();
		break;
		case $oTP->BANCO():
			switch($p->tipoTransferencia->id){
			case $oTT->TRANSFERENCIA():
				$valorTransfd += @$p->CALCULA_DOLLAR();
				$valorTransf += @$p->CALCULA_REAL();
			break;
			case $oTT->DEPOSITO():
				$valorDepositod += @$p->CALCULA_DOLLAR();
				$valorDeposito += @$p->CALCULA_REAL();
			break;
			case $oTT->TED():
				$valorTEDd += @$p->CALCULA_DOLLAR();
				$valorTED += @$p->CALCULA_REAL();
			break;
			case $oTT->DOC():
				$valorDOCd += @$p->CALCULA_DOLLAR();
				$valorDOC += @$p->CALCULA_REAL();
			break;
			}
		
		break;
	}
}
$tpl->DATA_INICIO = $oP->convdata($dataRelatorio,"mtn");
$tpl->DATA_FIM = $oP->convdata($dataFimRelatorio,"mtn");
$tpl->VALOR_ESPECIE_DOLLAR = $om->money($valorEspecied,"atb");
$tpl->VALOR_ESPECIE_REAL = $om->money($valorEspecie,"atb");

$tpl->VALOR_CHEQUE_DOLLAR = $om->money($valorChequed,"atb");
$tpl->VALOR_CHEQUE_REAL = $om->money($valorCheque,"atb");

$tpl->VALOR_CARTAO_DOLLAR = $om->money($valorCartaod,"atb");
$tpl->VALOR_CARTAO_REAL = $om->money($valorCartao,"atb");

$tpl->VALOR_DEBITO_DOLLAR = $om->money($valorDebitod,"atb");
$tpl->VALOR_DEBITO_REAL = $om->money($valorDebito,"atb");

$tpl->VALOR_TED_DOLLAR = $om->money($valorTEDd,"atb");
$tpl->VALOR_TED_REAL = $om->money($valorTED,"atb");

$tpl->VALOR_DOC_DOLLAR = $om->money($valorDOCd,"atb");
$tpl->VALOR_DOC_REAL = $om->money($valorDOC,"atb");

$tpl->VALOR_DEPOSITO_DOLLAR = $om->money($valorDepositod,"atb");
$tpl->VALOR_DEPOSITO_REAL = $om->money($valorDeposito,"atb");

$tpl->VALOR_TRANSFERENCIA_DOLLAR = $om->money($valorTransfd,"atb");
$tpl->VALOR_TRANSFERENCIA_REAL = $om->money($valorTransf,"atb");

$tpl->TOTAL_TOTAL_DOLLAR = $om->money($valorEspecied+$valorChequed+$valorCartaod+$valorDebitod+$valorTEDd+$valorDOCd+$valorDepositod+$valorTransfd,"atb");
$tpl->TOTAL_TOTAL_REAL = $om->money($valorEspecie+$valorCheque+$valorCartao+$valorDebito+$valorTED+$valorDOC+$valorDeposito+$valorTransf,"atb");

include("tupi.template.finalizar.php"); 
?>