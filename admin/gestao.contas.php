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
    <li class="active">Lista Contas a Pagar</li>
    </ul>';
}
$oC = new Conta();
$strDescricao = isset($_REQUEST['descricao']) ? $_REQUEST['descricao'] : "";
$strMes = isset($_REQUEST['mes']) ? $_REQUEST['mes'] : "";
$strAno = isset($_REQUEST['ano']) ? $_REQUEST['ano'] : "";
$totalContas = $oC->recuperaTotal($strDescricao,$strMes,$strAno);
$pagina = isset($_REQUEST['pagina']) ? $_REQUEST['pagina'] : 1;
$configPaginacao = $oC->paginar($totalContas,$pagina);
$rsContas = $oC->Pesquisa($configPaginacao['primeiroRegistro'],$configPaginacao['quantidadePorPagina'],$strDescricao,$strMes,$strAno);	

if($configPaginacao['totalPaginas'] > 1)
$tpl->block("BLOCK_PAGINACAO");
$tpl->PAGINA = $pagina;
$tpl->TOTAL_PAGINAS = $configPaginacao['totalPaginas'];
$tpl->PAGINA_ANTERIOR = $configPaginacao['paginaAnterior'];
$tpl->PROXIMA_PAGINA = $configPaginacao['proximaPagina'];
$tpl->DESCRICAO_FORM = $strDescricao;
$tpl->ANO_FORM = $strAno;
$tpl->MES_FORM = $strMes;

foreach($rsContas as $key => $Conta){
	$tpl->ID = $Conta->id;
	$tpl->DESCRICAO = $Conta->descricao;
	$tpl->VALOR = $oC->money($Conta->valorPagamento,"atb");
	$tpl->DATA = $oC->convdata($Conta->dataPagamento,"mtn");
	$tpl->TIPO = $Conta->tipo->descricao;
	$tpl->ID_HASH = $oC->md5_encrypt($Conta->id);
$tpl->block("BLOCK_ITEM_LISTA");	
}
include("tupi.template.finalizar.php"); 
?>