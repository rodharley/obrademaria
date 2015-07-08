<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 13;
include("tupi.seguranca.php");
$oMoeda = new Moeda();
$oPagamento = new Pagamento();
$oParticipante = new Participante();
$oGrupo = new Grupo();
$idPagamento = $oPagamento->md5_decrypt($_REQUEST['idPagamento']);
$oPagamento->getById($idPagamento);
$nomeGrupo = $oPagamento->participante->grupo->nomePacote;
$idGrupo = $oPagamento->md5_encrypt($oPagamento->participante->grupo->id);
$idParticHash = $oPagamento->md5_encrypt($oPagamento->participante->id);
$valorPagamento =  $oPagamento->valorPagamento;
$idPartic = 0;
$idGrupo = 0;
$ano = 0;
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
    <a href="participante.pagamentos.php?idGrupo='.$idGrupo.'&idParticipante='.$idParticHash.'">Pagamentos</a> <span class="divider">/</span>
    </li>
	<li>
    <a href="participante.abatimentos.php?idPagamento='.$_REQUEST['idPagamento'].'">Abatimentos</a> <span class="divider">/</span>
    </li>
    <li class="active">Editar Abatimento</li>
    </ul>';
}
$tpl->NOME_GRUPO = $nomeGrupo;
$tpl->MOEDA_GRUPO = $oPagamento->participante->grupo->moeda->descricao;
$tpl->VALOR_TOTAL_PAG = $oPagamento->moeda->cifrao." ".$oPagamento->money($valorPagamento,"atb");
$tpl->MOEDA_PAG = $oPagamento->moeda->descricao;
//$tpl->CIFRAO_PAG = $oPagamento->moeda->cifrao;
$tpl->ID_PAGAMENTO_HASH = $_REQUEST['idPagamento'];
$tpl->ACAO = 'IncluirAbatimento';

//carrega dados do pagamento
if(isset($_REQUEST['idAbatimento'])){
$oAbat = new Abatimento();
$oAbat->getById($oAbat->md5_decrypt($_REQUEST['idAbatimento']));
$tpl->ACAO = 'AlterarAbatimento';
$idPartic = $oAbat->participante->id;
$idGrupo = $oAbat->participante->grupo->id;
$ano = $oAbat->participante->grupo->ano;
$tpl->VALOR_ABATIMENTO = $oAbat->money($oAbat->valor,"atb");
$tpl->ID = $oAbat->id;

$rsPartic = $oParticipante->getRows(0,999,array("id"=>"asc"),array("grupo"=>" = ".$idGrupo));
foreach($rsPartic as $key => $partic){
$tpl->ID_PARTICIPANTE = $partic->id;
$tpl->LABEL_PARTICIPANTE = $partic->cliente->nomeCompleto;
if($idPartic == $partic->id){
	$tpl->SELECTED_PARTICIPANTE = "selected";
}
$tpl->block("BLOCK_PARTICIPANTE");
$tpl->clear("SELECTED_PARTICIPANTE");
}

$rsGrupos = $oGrupo->getRows(0,999,array(),array("status"=>"=".$oGrupo->STATUS_ANDAMENTO()));
foreach($rsGrupos as $key => $grupo){
$tpl->ID_GRUPO = $oAbat->md5_encrypt($grupo->id);
$tpl->LABEL_GRUPO = $grupo->nomePacote;
if($idGrupo == $grupo->id){
	$tpl->SELECTED_GRUPO = "selected";
}
$tpl->block("BLOCK_GRUPO");
$tpl->clear("SELECTED_GRUPO");
}


}

$rsAnos = $oGrupo->recuperaAnos();
//$rsGrupos = $oGrupo->getRows(0,999,array("ano"=>"desc"),array());	

while($row = mysql_fetch_array($rsAnos)){
	$tpl->ID_ANO = $row['ano'];
	$tpl->LABEL_ANO = $row['ano'];	
	if($ano == $row['ano']){
	$tpl->SELECTED_ANO = "selected";
}
	$tpl->block("BLOCK_ANO");	
}




include("tupi.template.finalizar.php"); 
?>