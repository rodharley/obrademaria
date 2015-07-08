<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 44;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '<ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="#">Gestão</a> <span class="divider">/</span>
    </li>
	<li>
    <a href="gestao.contas.php">Contas a Pagar</a> <span class="divider">/</span>
    </li>
    <li class="active">Editar Conta a Pagar</li>
    </ul>';
}

//carrega dados da conta
$oC = new Conta();
$oTC = new TipoConta;
$tpl->ACAO = 'IncluirConta';
if(isset($_REQUEST['idConta'])){
$tpl->ACAO = 'AlterarConta';
$oC->getById($oC->md5_decrypt($_REQUEST['idConta']));
$idTipo = $oC->tipo->id;
$tpl->ID = $Conta->id;
	$tpl->DESCRICAO = $oC->descricao;
	$tpl->VALOR_PAGAMENTO = $oC->money($oC->valorPagamento,"atb");
	$tpl->DATA_PAGAMENTO = $oC->convdata($oC->dataPagamento,"mtn");
	$tpl->ID_TIPO = $idTipo;
	$tpl->ID = $oC->id;
$tpl->block("BLOCK_CARREGA_TIPO_CONTA");
}

//tipo de conta
$rs = $oTC->getRows(0,999,array(),array());
foreach($rs as $key => $tp){
$tpl->ID_TIPOCONTA = $tp->id;
$tpl->LABEL_TIPOCONTA = $tp->descricao;
if($idTipo == $tp->id){
	$tpl->SELECTED_TIPOCONTA = "selected";
}
$tpl->block("BLOCK_TIPOCONTA");
$tpl->clear("SELECTED_TIPOCONTA");
}

include("tupi.template.finalizar.php"); 
?>