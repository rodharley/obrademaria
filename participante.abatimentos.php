<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 13;
include("tupi.seguranca.php");
$oMoeda = new Moeda();
$oPagamento = new Pagamento();
$idPagamento = $oPagamento->md5_decrypt($_REQUEST['idPagamento']);
$oPagamento->getById($idPagamento);
$nomePartic = $oPagamento->participante->cliente->nomeCompleto;
$nomeGrupo = $oPagamento->participante->grupo->nomePacote;
$idGrupo = $oPagamento->md5_encrypt($oPagamento->participante->grupo->id);
$idPartic = $oPagamento->md5_encrypt($oPagamento->participante->id);
$valorPagamento =  $oPagamento->valorPagamento;
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '    <ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="grupos.andamento.php">Grupos</a> <span class="divider">/</span>
    </li>
	<li>
    <a href="participante.lista.php?idGrupo='.$idGrupo.'">Participantes</a> <span class="divider">/</span>
    </li>
	<li>
    <a href="participante.pagamentos.php?idGrupo='.$idGrupo.'&idParticipante='.$idPartic.'">Pagamentos</a> <span class="divider">/</span>
    </li>
    <li class="active">Abatimentos</li>
    </ul>';
}
//configura o grupo na pagina
$tpl->NOME_PARTICIPANTE = $nomePartic;
$tpl->NOME_GRUPO = $nomeGrupo;
$tpl->MOEDA_GRUPO = $oPagamento->participante->grupo->moeda->descricao;
$tpl->CIFRAO_GRUPO = $oPagamento->participante->grupo->moeda->cifrao; 
$tpl->CIFRAO_PAGAMENTO = $oPagamento->moeda->cifrao; 
$tpl->VALOR_TOTAL_PAG = $oPagamento->moeda->cifrao." ".$oPagamento->money($valorPagamento,"atb");
$tpl->MOEDA_PAG = $oPagamento->moeda->descricao;
$tpl->ID_PAGAMENTO_HASH = $_REQUEST['idPagamento'];
$oA = new abatimento();
$rsAbat = $oA->getRows(0,999,array("id"=>"asc"),array("pagamento"=>" = ".$idPagamento));
$totalAbatGrupoMoeda = 0;
$totalAbatMoeda = 0;
$totalAbatPagMoeda = 0;
foreach($rsAbat as $key => $abatimento){
	$tpl->ID = $abatimento->id;
	$tpl->ID_HASH = $oA->md5_encrypt($abatimento->id);
	$tpl->PARTICIPANTE = $abatimento->participante->cliente->nomeCompleto;
	$tpl->VALOR = $oA->money($abatimento->valor,"atb");
	$tpl->VALOR_MOEDA = $oA->money($oPagamento->CALCULA_MOEDA($abatimento->valor,$oPagamento->participante->grupo->moeda->id),"atb");
	$totalAbatGrupoMoeda += $abatimento->valor;
	$totalAbatPagMoeda += $oPagamento->CALCULA_MOEDA($abatimento->valor,$oPagamento->participante->grupo->moeda->id);
	$tpl->block("BLOCK_ITEM_LISTA");
}
$tpl->TOTAL_MOEDA_PAG = $oA->money($totalAbatPagMoeda,"atb");
$tpl->TOTAL_MOEDA_GRUPO =  $oA->money($totalAbatGrupoMoeda,"atb");
$tpl->DIFERENCA = $oA->money(($valorPagamento-$totalAbatPagMoeda),"atb");

include("tupi.template.finalizar.php"); 
?>