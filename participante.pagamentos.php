<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 13;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '    <ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="grupos.andamento.php">Grupos</a> <span class="divider">/</span>
    </li>
	<li>
    <a href="participante.lista.php?idGrupo='.$_REQUEST['idGrupo'].'">Participantes</a> <span class="divider">/</span>
    </li>
    <li class="active">Pagamentos</li>
    </ul>';
}
//configura o grupo na pagina
$oGrupo = new Grupo();
$idGrupo = $oGrupo->md5_decrypt($_REQUEST['idGrupo']);
$oGrupo->getById($idGrupo);
$tpl->NOME_GRUPO = $oGrupo->nomePacote;
$tpl->ID_GRUPO_HASH = $_REQUEST['idGrupo'];
$tpl->ID_HASH = $_REQUEST['idParticipante'];
$tpl->CIFRAO_GRUPO = $oGrupo->moeda->cifrao;
$oP = new Pagamento();
$oA = new Abatimento();
$oParticipante = new Participante();
$idPartic = $oParticipante->md5_decrypt($_REQUEST['idParticipante']);
$oParticipante->getById($idPartic);
$rsPag = $oP->getRows(0,999,array("id"=>"asc"),array("participante"=>" = ".$idPartic,"cancelado"=>"=0"));
$tpl->NOME_PARTICIPANTE = $oParticipante->cliente->nomeCompleto;
$totalReal = 0;
$totalDollar = 0;
//$tpl->ID_PARTICIPANTE_HASH = $_REQUEST['idParticipante'];
foreach($rsPag as $key => $pagamento){
	$totalAbatMoedaGrupo = $oA->totalAbatimentos($pagamento->id);
	$totalAbatMoedaPagamento = $pagamento->CALCULA_MOEDA($totalAbatMoedaGrupo,$pagamento->participante->grupo->moeda->id);
	if ($pagamento->devolucao == 0)
	$tpl->STATUS_ABAT = $totalAbatMoedaPagamento < $pagamento->valorPagamento ? 'status-alert' : 'status-ok';
	else
	$tpl->STATUS_ABAT = 'status-ok';
	$tpl->ID_PAGAMENTO_HASH = $oP->md5_encrypt($pagamento->id);
	$tpl->DEV_PAG = $pagamento->devolucao;
	$tpl->TIPO = $pagamento->tipo->descricao;
	$tpl->MOEDA = $pagamento->moeda->descricao;
	$tpl->MOEDA_CIFRAO = $pagamento->moeda->cifrao;
	$tpl->DATA = $oP->convdata($pagamento->dataPagamento,"mtn");
	if($pagamento->devolucao == 0 ){
	$tpl->TRANZACAO = 'Crédito';
	$tpl->VALOR = $oP->money($pagamento->valorPagamento,"atb");
	$tpl->VALOR_DOLLAR = $oP->money($pagamento->CALCULA_DOLLAR(),"atb");
	$totalDollar += $pagamento->CALCULA_DOLLAR();
	$totalReal += $pagamento->CALCULA_REAL();
	}else{
	$tpl->TRANZACAO = 'Débito';
	$tpl->VALOR = $oP->money(-$pagamento->valorPagamento,"atb");
	$tpl->VALOR_DOLLAR = $oP->money(-$pagamento->CALCULA_DOLLAR(),"atb");
	$totalDollar -= $pagamento->CALCULA_DOLLAR();
	$totalReal -= $pagamento->CALCULA_REAL();
	}
	$tpl->CAMBIO = ($pagamento->cotacaoMoedaReal != 0 ? $pagamento->cotacaoMoedaReal." - " : "").$oP->money($pagamento->cotacaoReal,"atb");
	if($pagamento->obs  == "Cancelamento de Inscrição - Multa Recisória"){
	$tpl->block("BLOCK_MULTA");
	}else{
	$tpl->block("BLOCK_ACTIONS");
	}
	$tpl->block("BLOCK_ITEM_LISTA");
}
$tpl->TOTAL_REAL = $oP->money($totalReal,"atb");
$tpl->TOTAL_DOLLAR = $oP->money($totalDollar,"atb");
$tpl->DIFERENCA = $oP->money($oParticipante->recuperaValorPago(),"atb")."/".$oP->money($oParticipante->valorTotal,"atb");
include("tupi.template.finalizar.php"); 
?>