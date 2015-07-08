<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 8;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '<ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="#">Grupos</a> <span class="divider">/</span>
    </li>
    <li class="active">Lista Grupos</li>
    </ul>';
}
$oGrupo = new Grupo();
$totalGrupos = $oGrupo->recuperaTotalAndamento();
$oParticipante = new Participante();
$configPaginacao = $oGrupo->paginar($totalGrupos,isset($_REQUEST['pagina']) ? $_REQUEST['pagina'] : 1);
$rsGrupos = $oGrupo->getRows($configPaginacao['primeiroRegistro'],$configPaginacao['quantidadePorPagina'],array("dataEmbarque"=>"ASC"),array("status"=>"=".$oGrupo->STATUS_ANDAMENTO()));	

if($configPaginacao['totalPaginas'] > 1)
$tpl->block("BLOCK_PAGINACAO");
$tpl->PAGINA = isset($_REQUEST['pagina']) ? $_REQUEST['pagina'] : 1;
$tpl->TOTAL_PAGINAS = $configPaginacao['totalPaginas'];
$tpl->PAGINA_ANTERIOR = $configPaginacao['paginaAnterior'];
$tpl->PROXIMA_PAGINA = $configPaginacao['proximaPagina'];


foreach($rsGrupos as $key => $Grupo){
	$hoje =date("d/m/Y");
	$dataviagem =  $oGrupo->convdata($Grupo->dataEmbarque,"mtn");
	$dias = $oGrupo->diferenca_dias($hoje,$dataviagem);
	//$tpl->ID = $Grupo->id;
	$tpl->NOME = $Grupo->nomePacote;
	$tpl->STATUS_GRUPO = $Grupo->status->descricao;
	$tpl->QTD_PARTIC = $oParticipante->recuperaTotal($Grupo->id,"");
	$tpl->DATA_EMBARQUE = $dataviagem." - ".$dias." dias.";
	$tpl->DATA_CHEGADA = $oGrupo->convdata($Grupo->dataChegada,"mtn");
	$tpl->ID_HASH = $oGrupo->md5_encrypt($Grupo->id);
	$tpl->LINK_ROTEIRO = "docs/".$Grupo->roteiroAnexo;
	$tpl->LINK_PAUTA = "docs/".$Grupo->pautaAnexo;
	$tpl->MODELO_FICHA = $Grupo->modeloFicha;
$tpl->block("BLOCK_ITEM_LISTA");	
}
include("tupi.template.finalizar.php"); 
?>