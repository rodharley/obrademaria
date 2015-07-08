<?php 
include("tupi.inicializar.php"); 
$codTemplate = "relatorioHorizontal";
include("tupi.template.inicializar.php"); 
$codAcesso = 36;
include("tupi.seguranca.php");
//titulo do relatorio
$tpl->TITULO = "Relatório Geral por período";
$tpl->DATA_RELATORIO = "Data/Hora:" .date("d/m/Y h:i:s");
//declara as classes
$om = new Moeda();
$oP = new Pagamento();
$oTP = new TipoPagamento();
$oTT = new TipoTransferencia();
$oG = new Grupo();
$oPartic = new Participante();
$oC = new Cheque();
//TOTAIS GERAL
$custoDollarTotal = 0;
$recebimentoDollarTotal = 0;
$recebimentoRealTotal = 0;
$valorEspecieTotal = 0;
$valorCartaoTotal = 0;
$valorDebitoTotal = 0;
$valorChequeTotal = 0;
$valorTEDTotal = 0;
$valorDOCTotal = 0;
$valorTransfTotal = 0;
$valorDepositoTotal = 0;
$valorCreditoClienteTotal = 0;

$dataRelatorio = $om->convdata($_REQUEST['dataInicio'],"ntm");
$dataFimRelatorio = $om->convdata($_REQUEST['dataFim'],"ntm");
$rsGrupo = $oG->gruposComPagamentoPeriodo($dataRelatorio,$dataFimRelatorio);

foreach($rsGrupo as $key => $grupo){
	//declara os valores zerados
	$custoDollar = 0;
	$recebimentoDollar = 0;
	$recebimentoReal = 0;
	$valorEspecie = 0;
	$valorCartao = 0;
	$valorDebito = 0;
	$valorCheque = 0;
	$valorChequed = 0;
	$valorTED = 0;
	$valorDOC = 0;
	$valorTransf = 0;
	$valorDeposito = 0;
	$valorCreditoCliente = 0;


	$tpl->GRUPO = $grupo->nomePacote;
	$tpl->N_PARTICIPANTE = $oPartic->recuperaTotalAtivo($grupo->id);
	
	//PEGA OS CHEQUES
			$rsCheques = $oC->pesquisa($dataRelatorio,$dataFimRelatorio,"",0,$grupo->id);
			//somatorio dos cheques
			foreach($rsCheques as $key => $cheque){
			$valorChequed += @($cheque->valor/$cheque->pagamento->cotacaoReal);	
			$valorCheque += $cheque->valor;
			}
	
	//recupera os participantes
	$rsPartic = $oPartic->getRows(0,999,array(),array("grupo"=>"=".$grupo->id));
	foreach($rsPartic as $keyPart => $part){
		//somatorios do custo
		if($part->status->id != $oPartic->STATUS_DESISTENTE()){
			if($grupo->moeda->id == $om->DOLLAR())
				@$custoDollar += $part->custoTotal;
			else
				@$custoDollar += $part->custoTotal/$grupo->cotacaoCusto;
		}
		//RECUPERA TODOS OS PAGAMENTOS DO GRUPO NO PERIODO
		$rsPgt = $oP->getRows(0,999,array(),array("participante" => "=".$part->id,"dataPagamento"=> "between '".$dataRelatorio."' and '".$dataFimRelatorio."'", "cancelado"=>"=0"));
		foreach($rsPgt as $keyPag => $p){
			
			
			
				switch($p->tipo->id){
				case $oTP->DINHEIRO():
				//somatorios dos valores
				$recebimentoDollar +=  @$p->CALCULA_DOLLAR();
				$recebimentoReal +=  @$p->CALCULA_REAL();
				$valorEspecie += $p->CALCULA_REAL();
				break;
				case $oTP->CARTAO():
				//somatorios dos valores
					$recebimentoDollar +=  @$p->CALCULA_DOLLAR();
					$recebimentoReal +=  @$p->CALCULA_REAL();
				$valorCartao += $p->CALCULA_REAL();
				break;
				case $oTP->DEBITO():
				//somatorios dos valores
					$recebimentoDollar +=  @$p->CALCULA_DOLLAR();
					$recebimentoReal +=  @$p->CALCULA_REAL();
				$valorDebito += $p->CALCULA_REAL();
				break;
				case $oTP->CHEQUE():
				//$valorCheque += $p->CALCULA_REAL();
				break;
				case $oTP->CREDITO():
				//somatorios dos valores
					$recebimentoDollar +=  @$p->CALCULA_DOLLAR();
					$recebimentoReal +=  @$p->CALCULA_REAL();
				$valorCreditoCliente += $p->CALCULA_REAL();
				break;
				case $oTP->BANCO():
					//somatorios dos valores
					$recebimentoDollar +=  @$p->CALCULA_DOLLAR();
					$recebimentoReal +=  @$p->CALCULA_REAL();

					switch($p->tipoTransferencia->id){
						case $oTT->TRANSFERENCIA():
							$valorTransf += $p->CALCULA_REAL();
						break;
						case $oTT->DEPOSITO():
							$valorDeposito += $p->CALCULA_REAL();
						break;
						case $oTT->TED():
							$valorTED += $p->CALCULA_REAL();
						break;
						case $oTT->DOC():
							$valorDOC += $p->CALCULA_REAL();
						break;
					}
				
				break;
				}
		}//fim do loop de pagamentos
	}//fim do loop de participantes
	$tpl->CUSTO = $om->money($custoDollar,"atb");
	$tpl->RECEBIMENTOS_DOLLAR = $om->money($recebimentoDollar+$valorChequed,"atb");
	$tpl->RECEBIMENTOS_REAL = $om->money($recebimentoReal+$valorCheque,"atb");
	$tpl->VALOR_CHEQUE = $om->money($valorCheque,"atb");
	$tpl->VALOR_ESPECIE = $om->money($valorEspecie,"atb");
	$tpl->VALOR_CARTAO = $om->money($valorCartao,"atb");
	$tpl->VALOR_DEBITO = $om->money($valorDebito,"atb");
	$tpl->VALOR_CREDITO = $om->money($valorCreditoCliente,"atb");
	$tpl->VALOR_DEPOSITO = $om->money($valorDeposito,"atb");
	$tpl->VALOR_TRANSFERENCIA = $om->money($valorTransf,"atb");
	$tpl->VALOR_TED = $om->money($valorTED,"atb");
	$tpl->VALOR_DOC = $om->money($valorDOC,"atb");
	
	$custoDollarTotal += $custoDollar;
	$recebimentoDollarTotal += $recebimentoDollar+$valorChequed;
	$recebimentoRealTotal += $recebimentoReal+$valorCheque;
	$valorEspecieTotal += $valorEspecie;
	$valorCartaoTotal += $valorCartao;
	$valorDebitoTotal += $valorDebito;
	$valorChequeTotal += $valorCheque;
	$valorTEDTotal += $valorTED;
	$valorDOCTotal += $valorDOC;
	$valorTransfTotal += $valorTransf;
	$valorDepositoTotal += $valorDeposito;
	$valorCreditoClienteTotal += $valorCreditoCliente;
	
	$tpl->block("BLOCK_ITEM_LISTA");
}//fim do loop de grupos

$tpl->TOTAL_CUSTO = $om->money($custoDollarTotal,"atb");
$tpl->TOTAL_R_DOLLAR = $om->money($recebimentoDollarTotal,"atb");
$tpl->TOTAL_R_REAL = $om->money($recebimentoRealTotal,"atb");
$tpl->TOTAL_CHEQUE = $om->money($valorChequeTotal,"atb");
$tpl->TOTAL_ESPECIE = $om->money($valorEspecieTotal,"atb");
$tpl->TOTAL_CARTAO = $om->money($valorCartaoTotal,"atb");
$tpl->TOTAL_DEBITO = $om->money($valorDebitoTotal,"atb");
$tpl->TOTAL_CREDITO = $om->money($valorCreditoClienteTotal,"atb");
$tpl->TOTAL_DEPOSITO = $om->money($valorDepositoTotal,"atb");
$tpl->TOTAL_TRANSFERENCIA = $om->money($valorTransfTotal,"atb");
$tpl->TOTAL_TED = $om->money($valorTEDTotal,"atb");
$tpl->TOTAL_DOC = $om->money($valorDOCTotal,"atb");
//$tpl->DATA_ATUAL = date("d/m/Y");
$tpl->DATA_INICIO = $oP->convdata($dataRelatorio,"mtn");
$tpl->DATA_FIM = $oP->convdata($dataFimRelatorio,"mtn");
include("tupi.template.finalizar.php"); 
?>