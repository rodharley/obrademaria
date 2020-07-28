<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php");
$codAcesso = 6; 
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '    <ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="#">Cadastro</a> <span class="divider">/</span>
    </li>
    <li class="active">Lista Perfil</li>
    </ul>';
}
$oPerfil = new Perfil();
$totalPerfis = $oPerfil->recuperaTotal();

$configPaginacao = $oPerfil->paginar($totalPerfis,isset($_REQUEST['pagina']) ? $_REQUEST['pagina'] : 1);
$rsPerfis = $oPerfil->getRows($configPaginacao['primeiroRegistro'],$configPaginacao['quantidadePorPagina'],array(),array());	

if($configPaginacao['totalPaginas'] > 1)
$tpl->block("BLOCK_PAGINACAO");

$tpl->TOTAL_PAGINAS = $configPaginacao['totalPaginas'];
$tpl->PAGINA_ANTERIOR = $configPaginacao['paginaAnterior'];
$tpl->PROXIMA_PAGINA = $configPaginacao['proximaPagina'];


foreach($rsPerfis as $key => $perfil){
	$tpl->ID = $perfil->id;
	$tpl->DESCRICAO = $perfil->descricao;
	$tpl->ID_HASH = $oPerfil->md5_encrypt($perfil->id);
$tpl->block("BLOCK_ITEM_LISTA");	
}
include("tupi.template.finalizar.php"); 
?>