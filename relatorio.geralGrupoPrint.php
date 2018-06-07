<?php
include("tupi.inicializar.php");
$codTemplate = "relatorioHorizontal";
include("tupi.template.inicializar.php");
$codAcesso = 38;
include("tupi.seguranca.php");
set_time_limit (0);

$oGrupo = new Grupo();
$oMoeda = new Moeda();
$oPagamento = new Pagamento();
$oAbatimento = new Abatimento();
$oTP = new TipoPagamento();
$oTT = new TipoTransferencia();
$oCheque = new Cheque();
$oGrupo->getById($oGrupo->md5_decrypt($_REQUEST['idGrupo']));
$tpl->COD_GRUPO = str_pad($oGrupo->id,7,"0", STR_PAD_LEFT);
$tpl->NOME_GRUPO = $oGrupo->nomePacote;
$tpl->DATA_RELATORIO = "Data/Hora:" .date("d/m/Y h:i:s");
//recupera participantes aprovados
$opartic = new Participante();
$rs = $opartic->participantesGrupo($oGrupo->id);
$cont = 1;
$TOTAL_CUSTO_DOLLAR = 0;
$TOTAL_CUSTO_REAL =0;
$TOTAL_CARTAO = 0;
$TOTAL_ESPECIE =0;
$TOTAL_TRANSF =0;
$TOTAL_DEPOSITO =0;
$TOTAL_DEBITO =0;
$TOTAL_TED =0;
$TOTAL_DOC =0;
$TOTAL_CREDITO =0; 
$TOTAL_CHEQUE =0;
$TOTAL_RECEBIMENTO_DOLLAR =0;
$TOTAL_RECEBIMENTO_REAL =0;
foreach($rs as $key => $p){
$possuiCheques = 0;
	$tpl->ID = $cont;
	$tpl->PARTICIPANTE = $p->cliente->nomeCompleto;
	$tpl->STATUS = $p->status->descricao;

	//calcula custo:
	$custo = $p->custoTotal;
	$cotCustpo = $oGrupo->cotacaoCusto  == 0 ? 1 : $oGrupo->cotacaoCusto;
	if($oGrupo->moeda->id == $oMoeda->DOLLAR()){
	$custoDollar = $custo;
	$custoReal = $custo * $oGrupo->cotacaoCusto;
	}else{
	$custoDollar = $custo / $cotCustpo;
	$custoReal = $custo;
	}
	

	//busca os abatimentos do participante
		$rsAbat = $oAbatimento->abatimentosParticipantes($p->id);
		$contPag = 0;
		$totalAbatParticipanteReal = 0;
		$totalAbatParticipanteDollar = 0;
	
foreach($rsAbat as $keyAbat => $abat){
		$contPag++;
		//busca o pagamento ativo do participante
		$oPagamento->getById($abat->pagamento->id);
		//somatorios dos valores
					switch($oPagamento->tipo->id){
					case $oTP->DINHEIRO():
					$TOTAL_ESPECIE += $abat->getValorReal();
					break;
					case $oTP->CARTAO():
					$TOTAL_CARTAO += $abat->getValorReal();
					break;
					case $oTP->DEBITO():
					$TOTAL_DEBITO += $abat->getValorReal();
					break;
					case $oTP->CHEQUE():
					$TOTAL_CHEQUE +=  $abat->getValorReal();
					break;
					case $oTP->CREDITO():
					$TOTAL_CREDITO +=  $abat->getValorReal();
					break;
					case $oTP->BANCO():
						$oTT->getById($oPagamento->tipoTransferencia->id);
						$tipoPag = $oTT->descricao;
						switch($oPagamento->tipoTransferencia->id){
							case $oTT->TRANSFERENCIA():
								$TOTAL_TRANSF +=  $abat->getValorReal();
							break;
							case $oTT->DEPOSITO():
								$TOTAL_DEPOSITO +=  $abat->getValorReal();
							break;
							case $oTT->TED():
								$TOTAL_TED +=  $abat->getValorReal();
							break;
							case $oTT->DOC():
								$TOTAL_DOC +=  $abat->getValorReal();
							break;
						}

					break;
					}


		$tpl->VALOR_ABAT_REAL = $oMoeda->money($abat->getValorReal(),"atb");
		$tpl->VALOR_ABAT_DOLLAR = $oMoeda->money($abat->getValorDollar(),"atb");
		$totalAbatParticipanteReal += $abat->getValorReal();
		$totalAbatParticipanteDollar += $abat->getValorDollar();
		if($oPagamento->devolucao == 0)
		$tpl->PAG_TRANSACAO = 'Crédito';
		else
		$tpl->PAG_TRANSACAO = 'Débito';
		$tpl->N_PAG = $contPag;
		$tpl->PAG_PARTICIPANTE = $oPagamento->participante->cliente->nomeCompleto;
		$tpl->GRUPO =  $oPagamento->participante->grupo->nomePacote;
		$tpl->TIPO = $oPagamento->tipo->descricao;
		$tpl->DATA_PAG =  $oMoeda->convdata($oPagamento->dataPagamento,"mtn");
		$tpl->MODEA = $oPagamento->moeda->descricao;
		$tpl->VALOR_REAL = $oMoeda->money($oPagamento->devolucao == 0 ? $oPagamento->CALCULA_REAL() : -$oPagamento->CALCULA_REAL(),"atb");
		$tpl->VALOR_DOLLAR = $oMoeda->money($oPagamento->devolucao == 0 ? $oPagamento->CALCULA_DOLLAR() : -$oPagamento->CALCULA_DOLLAR(),"atb");
		$tpl->COTACAO = $oMoeda->money($oPagamento->cotacaoReal,"atb");

		//se for cheque imprimi lista de cheques
		if($oPagamento->tipo->id == $oTP->CHEQUE()){
		$possuiCheques = 1;
		$rsCheques = $oCheque->getRows(0,999,array(),array("pagamento" => "=".$oPagamento->id));

		foreach($rsCheques as $keyCh => $cheque){
		$tpl->BANCO = $oPagamento->banco->sigla;
		$tpl->EMISSOR = $cheque->emissor->nomeCompleto;
		$tpl->CHEQUE_DATA = $oMoeda->convdata($cheque->dataCompensacao,"mtn");
		$tpl->CHEQUE_NUMERO = $cheque->numeroCheque;
		$tpl->CHEQUE_VALOR = $oMoeda->money($cheque->valor,"atb");
		$tpl->block("BLOCK_ITEM_CHEQUE");
		}
		}

$tpl->block("BLOCK_ITEM_PAGAMENTOS");
}//fim do loop de abatimentos

$recebimentosDollar = $totalAbatParticipanteDollar;//$p->recuperaValorTodosAbatimentos($oMoeda->DOLLAR());
    $recebimentosReal = $totalAbatParticipanteReal;//$p->recuperaValorTodosAbatimentos($oMoeda->REAL());
    //alimenta a primeira linha do relatorio
    $tpl->CAMBIO_CUSTO = $oMoeda->money($oGrupo->cotacaoCusto,"atb");
    $tpl->CUSTO_DOLLAR = $oMoeda->money($custoDollar,"atb");
    $tpl->CUSTO_REAL = $oMoeda->money($custoReal,"atb");
    $tpl->RECEBIMENTOS_DOLLAR = $oMoeda->money($recebimentosDollar,"atb");
    $tpl->RECEBIMENTOS_REAL = $oMoeda->money($recebimentosReal,"atb");
    //SOMATORIOS DE VALORES
    $TOTAL_RECEBIMENTO_DOLLAR +=  $recebimentosDollar;
    $TOTAL_RECEBIMENTO_REAL +=  $recebimentosReal;
    $TOTAL_CUSTO_DOLLAR +=  $custoDollar;
    $TOTAL_CUSTO_REAL +=  $custoReal;



$tpl->TOTAL_ABAT_PARTIC_DOLLAR = $oMoeda->money($totalAbatParticipanteDollar,"atb");
$tpl->TOTAL_ABAT_PARTIC_REAL = $oMoeda->money($totalAbatParticipanteReal,"atb");
if($possuiCheques == 1){
$tpl->block("BLOCK_CHEQUES");
}

$tpl->block("BLOCK_BLOCO_P_CLIENTE");
$tpl->block("BLOCK_PARTICIPANTE");
$cont++;
}// fim do loop de participantes
//TOTALIZADOR
$tpl->TOTAL_CHEQUE = $oMoeda->money($TOTAL_CHEQUE,"atb");
$tpl->TOTAL_CARTAO = $oMoeda->money($TOTAL_CARTAO,"atb");
$tpl->TOTAL_DEBITO = $oMoeda->money($TOTAL_DEBITO,"atb");
$tpl->TOTAL_ESPECIE = $oMoeda->money($TOTAL_ESPECIE,"atb");
$tpl->TOTAL_DEPOSITO = $oMoeda->money($TOTAL_DEPOSITO,"atb");
$tpl->TOTAL_TRANSF = $oMoeda->money($TOTAL_TRANSF,"atb");
$tpl->TOTAL_TED = $oMoeda->money($TOTAL_TED,"atb");
$tpl->TOTAL_DOC = $oMoeda->money($TOTAL_DOC,"atb");
$tpl->TOTAL_CREDITO = $oMoeda->money($TOTAL_CREDITO,"atb");
//RESULTADO
$tpl->CUSTO_TOTAL = $oMoeda->money($TOTAL_CUSTO_REAL,"atb");
$tpl->CUSTO_TOTAL_DOLLAR = $oMoeda->money($TOTAL_CUSTO_DOLLAR,"atb");
$tpl->RECEITA_TOTAL = $oMoeda->money($TOTAL_RECEBIMENTO_REAL,"atb");
$tpl->RECEITA_TOTAL_DOLAR = $oMoeda->money($TOTAL_RECEBIMENTO_DOLLAR,"atb");
$tpl->LUCRO_TOTAL = $oMoeda->money($TOTAL_RECEBIMENTO_REAL - $TOTAL_CUSTO_REAL,"atb");
$tpl->LUCRO_TOTAL_DOLLAR = $oMoeda->money($TOTAL_RECEBIMENTO_DOLLAR - $TOTAL_CUSTO_DOLLAR,"atb");
include("tupi.template.finalizar.php");
?>