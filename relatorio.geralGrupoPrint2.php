<?php 
include("tupi.inicializar.php"); 
$codTemplate = "relatorioHorizontal";
include("tupi.template.inicializar.php"); 
$codAcesso = 38;
include("tupi.seguranca.php");
$oGrupo = new Grupo();
$oMoeda = new Moeda();
$oPagamento = new Pagamento();
$oAbatimento = new Abatimento();
$oTP = new TipoPagamento();
$oTT = new TipoTransferencia();
$oGrupo->getById($oGrupo->md5_decrypt($_REQUEST['idGrupo']));
$tpl->COD_GRUPO = str_pad($oGrupo->id,7,"0", STR_PAD_LEFT);
$tpl->NOME_GRUPO = $oGrupo->nomePacote;
$tpl->DATA_RELATORIO = "Data/Hora:" .date("d/m/Y h:i:s");
//recupera participantes aprovados
$opartic = new Participante();
$rs = $opartic->getRows(0,999,array("id"=>"asc"),array("grupo"=>"=".$oGrupo->id));
$cont = 1;
foreach($rs as $key => $p){
	$tpl->ID = $cont;
	$tpl->PARTICIPANTE = $p->cliente->nomeCompleto;
	$tpl->STATUS = $p->status->descricao;
	//calcula custo:
	$custo = $p->custoTotal;
	if($oGrupo->moeda->id == $oMoeda->DOLLAR()){
	$custoDollar = $custo;
	$custoReal = $custo * $oGrupo->cotacaoCusto;
	}else{
	$custoDollar = $custo / $oGrupo->cotacaoCusto;
	$custoReal = $custo;
	}
	$recebimentosDollar = $p->recuperaValorTodosAbatimentos($oMoeda->DOLLAR());
	$recebimentosReal = $p->recuperaValorTodosAbatimentos($oMoeda->REAL());
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
	//busca os pagamentos ativos do participante 
	$rsAbat = $oAbatimento->getRows(0,999,array(),array("participante" => "=".$p->id));
		foreach($rsAbat as $keyAbat => $abat){
			$pag = $abat->pagamento;
			$tipoPag = $pag->tipo->descricao;
				//somatorios dos valores			
					switch($pag->tipo->id){
					case $oTP->DINHEIRO():
					$TOTAL_ESPECIE += $abat->getValorReal();				
					break;
					case $oTP->CARTAO():
					$TOTAL_CARTAO += $abat->getValorReal();
					break;
					case $oTP->CHEQUE():
					$TOTAL_CHEQUE +=  $abat->getValorReal();
					break;
					case $oTP->CREDITO():
					$TOTAL_CREDITO +=  $abat->getValorReal();
					break;
					case $oTP->BANCO():
						$oTT->getById($pag->tipoTransferencia->id);
						$tipoPag = $oTT->descricao;
						switch($pag->tipoTransferencia->id){
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
			$tpl->EMISSOR = $pag->participante->cliente->nomeCompleto;
			$tpl->FINALIDADE = $pag->finalidade->descricao;
			$tpl->TIPO = $tipoPag;	
			$tpl->DATA_PAG =  $oMoeda->convdata($pag->dataPagamento,"mtn");
			$tpl->MODEA = $pag->moeda->descricao;
			$tpl->VALOR_REAL = $oMoeda->money($abat->getValorReal(),"atb");
			$tpl->VALOR_DOLLAR = $oMoeda->money($abat->getValorDollar(),"atb");
			$tpl->COTACAO = $oMoeda->money($pag->cotacaoReal,"atb");
			$tpl->block("BLOCK_ITEM_LISTA");		
			}//fim do loop de pagamentos
	
	
	
	
	
	$tpl->block("BLOCK_PARTICIPANTE");
	$cont++;
}// fim do loop de participantes
//TOTALIZADOR
$tpl->TOTAL_CHEQUE = $oMoeda->money($TOTAL_CHEQUE,"atb");
$tpl->TOTAL_CARTAO = $oMoeda->money($TOTAL_CARTAO,"atb");
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