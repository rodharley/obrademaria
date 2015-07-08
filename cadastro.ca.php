<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 46;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '<ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="#">Cadastro</a> <span class="divider">/</span>
    </li>
    <li class="active">Lista Companhias aéreas</li>
    </ul>';
}
$oCA = new CompanhiaAerea();
$totalCas = $oCA->recuperaTotal();
$pagina = isset($_REQUEST['pagina']) ? $_REQUEST['pagina'] : 1;
$configPaginacao = $oCA->paginar($totalCas,$pagina);
$rsCas = $oCA->getRows($configPaginacao['primeiroRegistro'],$configPaginacao['quantidadePorPagina'],array("descricao"=>"ASC"),array());	

if($configPaginacao['totalPaginas'] > 1)
$tpl->block("BLOCK_PAGINACAO");
$tpl->PAGINA = $pagina;
$tpl->TOTAL_PAGINAS = $configPaginacao['totalPaginas'];
$tpl->PAGINA_ANTERIOR = $configPaginacao['paginaAnterior'];
$tpl->PROXIMA_PAGINA = $configPaginacao['proximaPagina'];


foreach($rsCas as $key => $CA){
	$tpl->ID = $CA->id;
	$tpl->DESCRICAO = $CA->descricao;
	$tpl->LOGOMARCA = $CA->logomarca;
	$tpl->ID_HASH = $oCA->md5_encrypt($CA->id);
$tpl->block("BLOCK_ITEM_LISTA");	
}
include("tupi.template.finalizar.php"); 
?>