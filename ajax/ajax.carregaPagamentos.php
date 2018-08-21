<?
include("../tupi.inicializar.php");
include("../tupi.template.inicializar.php"); 
$oTipoP = new TipoPagamento();
$oMoeda = new moeda();
$oBanco = new Banco();
$oTipot = new TipoTransferencia();
$oPag = new Pagamento();
$oCred = new Credito();
$oCliente = new Cliente();
$oCheque = new Cheque();
$oCarne = new Carne();
$oParticipante = new Participante();
$oParticipante->getById($_REQUEST['idParticipante']);
$tpl->ID_PARTICIPANTE_REP = $_REQUEST['idParticipante'];
$tpl->ID_TIPO = $_REQUEST['idTipo'];
$idMoedaEdita = 0;
$idBancoEdita = 0;
$idBandeiraEdita = 0;
$idTipoTransf = 0;
$idCredito = 0;
$tpl->CAMBIO_DOLLAR_REAL = 0;
$tpl->COD_AUTORIZACAO = "";
$tpl->DATA_COMPENSACAO = "";
$tpl->NUMERO_CHEQUE = "";
$tpl->CAMBIO_MOEDA_REAL = 0;

$tpl->NOME_EMISSOR = $oParticipante->cliente->nomeCompleto."-".$oParticipante->cliente->id;
if(isset($_REQUEST['idPagamento']) && strlen($_REQUEST['idPagamento']) > 0){
$oPag->getById($_REQUEST['idPagamento']);

$idMoedaEdita = $oPag->moeda->id;	
$tpl->ID = $_REQUEST['idPagamento'];
}

switch($_REQUEST['idTipo']){
case $oTipoP->DINHEIRO() :
	$rsm = $oMoeda->getRows();

	foreach($rsm as $key => $row){
		$tpl->ID_MOEDA = $row->id;
		$tpl->LABEL_MOEDA = $row->descricao;
		if(	$idMoedaEdita == $row->id)		
			$tpl->SELECTED_MOEDA = 'selected="selected"';
		$tpl->block("BLOCK_MOEDA");
		$tpl->clear("SELECTED_MOEDA");
	}
	$tpl->CAMBIO_MOEDA_REAL = $oPag->money($oPag->cotacaoMoedaReal,"atb");
	$tpl->CAMBIO_DOLLAR_REAL = $oPag->money($oPag->cotacaoReal,"atb");
	$tpl->VALOR_PAGAMENTO =	$oPag->money($oPag->valorPagamento,"atb");
	$tpl->block('BLOCK_PG_DINHEIRO');
break;
case $oTipoP->CARTAO() :
	$tpl->COD_AUTORIZACAO = $oPag->codAutorizacao;
	$tpl->VALOR_PARCELA = $oPag->money($oPag->valorParcela,"atb");
	$tpl->NUMERO_CARTAO = $oPag->numeroCartao;
	$tpl->CAMBIO_DOLLAR_REAL = $oPag->money($oPag->cotacaoReal,"atb");
	$tpl->VALOR_PAGAMENTO =	$oPag->money($oPag->valorPagamento,"atb");
	if($oPag->bandeira != null)
		$idBandeiraEdita = $oPag->bandeira->id;
	$tpl->PARCELA_CARTAO = $oPag->parcela;
	$oband = new BandeiraCartao();
	$rs = $oband->getRows(0,999,array(),array());
	foreach($rs as $key => $row){
		$tpl->ID_BANDEIRA = $row->id;
		$tpl->IMG_BANDEIRA = $row->imagem;
		if(	$idBandeiraEdita == $row->id)		
			$tpl->SELECTED_BANDEIRA = 'checked="checked"';
		$tpl->block('BLOCK_BANDEIRA');
		$tpl->clear("SELECTED_BANDEIRA");
	}
	$tpl->block('BLOCK_PG_CARTAO');
break;
case $oTipoP->DEBITO() :
	$tpl->CAMBIO_DOLLAR_REAL = $oPag->money($oPag->cotacaoReal,"atb");
	$tpl->VALOR_PAGAMENTO =	$oPag->money($oPag->valorPagamento,"atb");
	$tpl->NUMERO_CARTAO = $oPag->numeroCartao;
    $tpl->COD_AUTORIZACAO = $oPag->codAutorizacao;
	if($oPag->bandeira != null)
		$idBandeiraEdita = $oPag->bandeira->id;
	$oband = new BandeiraCartao();
	$rs = $oband->getRows(0,999,array(),array());
	foreach($rs as $key => $row){
		$tpl->ID_BANDEIRA = $row->id;
		$tpl->IMG_BANDEIRA = $row->imagem;
		if(	$idBandeiraEdita == $row->id)		
			$tpl->SELECTED_BANDEIRA = 'checked="checked"';
		$tpl->block('BLOCK_BANDEIRA_DEB');
		$tpl->clear("SELECTED_BANDEIRA");
	}
	$tpl->block('BLOCK_PG_DEBITO');
break;
case $oTipoP->CHEQUE() :
	$rsm = $oBanco->getRows(0,999,array("codigo"=>"asc"));
	if($oPag->banco != null)
	$idBancoEdita = $oPag->banco->id;
	$tpl->NOMES = $oCliente->listaNomesIds();
	if($oPag->id != NULL)
	$tpl->NOME_EMISSOR = $oPag->emissorCheque->nomeCompleto."-".$oPag->emissorCheque->id;
	foreach($rsm as $key => $row){
		$tpl->ID_BANCO = $row->id;
		$tpl->LABEL_BANCO = $row->codigo."-".$row->nome;	
		if(	$idBancoEdita == $row->id)		
			$tpl->SELECTED_BANCO = 'selected="selected"';	
		$tpl->block("BLOCK_BANCO");
		$tpl->clear("SELECTED_BANCO");
	}
	for($i = 1 ;$i <= 10 ; $i++){
		if($i == 1)
		$tpl->REQUIRED = 'required';
		else
		$tpl->REQUIRED = '';
		$tpl->LOOP = $i;
		if($oPag->id  != null){
		$rsCheques = $oCheque->getRows(0,999,array("id"=>"asc"),array("parcela"=>"=".$i,"pagamento"=>"=".$oPag->id));
		if(count($rsCheques) > 0){
			$cheque = $rsCheques[0];
			$tpl->NUMERO_CHEQUE = $cheque->numeroCheque;
			$tpl->DATA_COMPENSACAO = $oPag->convdata($cheque->dataCompensacao,"mtn");
			$tpl->VALOR_CHEQUE = $oPag->money($cheque->valor,"atb");
		}
		}
		$tpl->block("BLOCK_N_CHEQUE");
		$tpl->block("BLOCK_DATA_CHEQUE");
		$tpl->block("BLOCK_VALOR_CHEQUE");
		$tpl->clear('NUMERO_CHEQUE');
		$tpl->clear('DATA_COMPENSACAO');
		$tpl->clear('VALOR_CHEQUE');
	}
	$tpl->CAMBIO_DOLLAR_REAL = $oPag->money($oPag->cotacaoReal,"atb");
	//$tpl->NUMERO_CHEQUE = $oPag->numeroCheque;
	//$tpl->DATA_COMPENSACAO = $oPag->dataCompensacao != "" && $oPag->dataCompensacao != "0000-00-00" ? $oPag->convdata($oPag->dataCompensacao,"mtn") : "";
	$tpl->block('BLOCK_PG_CHEQUE');
	
break;
case $oTipoP->BANCO() :
	$rsm = $oTipot->getRows();
	if($oPag->tipoTransferencia != null)
	$idTipoTransf = $oPag->tipoTransferencia->id;
	foreach($rsm as $key => $row){
		$tpl->ID_TIPO_TRANSF = $row->id;
		$tpl->LABEL_TIPO_TRANSF = $row->descricao;
		if($idTipoTransf == $row->id)		
			$tpl->SELECTED_TIPO_TRANSF = 'selected="selected"';	
		$tpl->block("BLOCK_TIPO_TRANSF");
		$tpl->clear("SELECTED_TIPO_TRANSF");		
	}
	$tpl->CAMBIO_DOLLAR_REAL = $oPag->money($oPag->cotacaoReal,"atb");
	$tpl->VALOR_PAGAMENTO =	$oPag->money($oPag->valorPagamento,"atb");
	$tpl->block('BLOCK_PG_BANCO');
break;
case $oTipoP->CREDITO() :
	if($oPag->creditoCliente != null)	
	$idCredito = $oPag->creditoCliente->id;
	$rsm = $oCred->getRows(0,999,array(),array("cliente"=>"=".$oParticipante->cliente->id , "bitUtilizado"=>"=0"));
	if($idCredito != 0 && strlen($idCredito) > 0){
		$oCredito = new Credito();
		$oCredito->getById($idCredito);
		array_push($rsm,$oCredito);
		}
	foreach($rsm as $key => $row){
		$tpl->ID_CREDITO = $row->id;
		$tpl->LABEL_CREDITO = $row->moeda->cifrao." ".$oCred->money($row->valor,"atb")." ".$row->moeda->plural."-".$oTipoP->money($row->cotacaoReal,"atb")."-".$oTipoP->convdata($row->data,"mtn");
		if($idCredito == $row->id)		
			$tpl->SELECTED_CREDITO = 'selected="selected"';	
		$tpl->block("BLOCK_CREDITO");
		$tpl->clear("SELECTED_CREDITO");		
	}
	$tpl->CAMBIO_DOLLAR_REAL = $oPag->money($oPag->cotacaoReal,"atb");
	$tpl->block('BLOCK_PG_CREDITO');
break;

case $oTipoP->CARNE() :
	$oCarne = new Carne();
	if($oPag->id != NULL){
	$qtd = isset($_REQUEST['nparcela']) ? $_REQUEST['nparcela'] : $oPag->parcela;
	$tpl->PARCELA_CARNE = $qtd;
	for($i=1; $i <= $qtd; $i++){
	$tpl->LOOP = $i;
	$rsCarnes = $oCarne->getRows(0,999,array("id"=>"asc"),array("parcela"=>"=".$i,"pagamento"=>"=".$oPag->id));
		if(count($rsCarnes) > 0){
			$carne = $rsCarnes[0];			
			$tpl->DATA_CARNE = $oPag->convdata($carne->dataVencimento,"mtn");
			$tpl->VALOR_CARNE = $oPag->money($carne->valor,"atb");
		}	
	$tpl->block("BLOCK_VALOR_CARNE");
	$tpl->block("BLOCK_DATA_CARNE");
	$tpl->clear("VALOR_CARNE");
	$tpl->clear("DATA_CARNE");
	}
	}else{	
	$qtd = isset($_REQUEST['nparcela']) ? $_REQUEST['nparcela'] : 1;
	$tpl->PARCELA_CARNE = $qtd;
	for($i=1; $i <= $qtd; $i++){
	$tpl->LOOP = $i;
	$tpl->block("BLOCK_VALOR_CARNE");
	$tpl->block("BLOCK_DATA_CARNE");
	}	
	}
	$tpl->CAMBIO_DOLLAR_REAL = $oPag->money($oPag->cotacaoReal,"atb");	
	$tpl->block('BLOCK_PG_CARNE');
break;

}
include("../tupi.template.finalizar.php"); 
?>