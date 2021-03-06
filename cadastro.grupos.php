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
    <a href="#">Cadastro</a> <span class="divider">/</span>
    </li>
    <li class="active">Lista Grupos</li>
    </ul>';
}
$oGrupo = new Grupo();
$totalGrupos = $oGrupo->recuperaTotal(isset($_REQUEST['ano']) ? $_REQUEST['ano'] : "");
$pagina = isset($_REQUEST['pagina']) ? $_REQUEST['pagina'] : 1;
$configPaginacao = $oGrupo->paginar($totalGrupos,$pagina );
$rsGrupos = $oGrupo->pesquisa($configPaginacao['primeiroRegistro'],$configPaginacao['quantidadePorPagina'],isset($_REQUEST['ano']) ? $_REQUEST['ano'] : "" );	

if($configPaginacao['totalPaginas'] > 1)
$tpl->block("BLOCK_PAGINACAO");

$tpl->TOTAL_PAGINAS = $configPaginacao['totalPaginas'];
$tpl->PAGINA_ANTERIOR = $configPaginacao['paginaAnterior'];
$tpl->PROXIMA_PAGINA = $configPaginacao['proximaPagina'];
$tpl->PAGINA = $pagina;
$tpl->ANO = isset($_REQUEST['ano']) ? $_REQUEST['ano'] : "";

foreach($rsGrupos as $key => $Grupo){
	$dataviagem =  $oGrupo->convdata($Grupo->dataEmbarque,"mtn");
	$tpl->ID = $Grupo->id;
	$tpl->NOME = $Grupo->nomePacote;
	$tpl->ANO_GRUPO = $Grupo->ano;
	$tpl->DATA_EMBARQUE = $dataviagem;
	$tpl->STATUS_GRUPO = $Grupo->status->descricao;
	$tpl->ID_HASH = $oGrupo->md5_encrypt($Grupo->id);
$tpl->block("BLOCK_ITEM_LISTA");	
}
include("tupi.template.finalizar.php"); 
?>