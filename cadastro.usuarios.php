<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 7;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '<ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="#">Cadastro</a> <span class="divider">/</span>
    </li>
    <li class="active">Lista Usuários</li>
    </ul>';
}
$oUsuario = new Usuario();
$totalUsuarios = $oUsuario->recuperaTotal();

$configPaginacao = $oUsuario->paginar($totalUsuarios,isset($_REQUEST['pagina']) ? $_REQUEST['pagina'] : 1);
$rsUsuarios = $oUsuario->getRows($configPaginacao['primeiroRegistro'],$configPaginacao['quantidadePorPagina'],array(),array());	

if($configPaginacao['totalPaginas'] > 1)
$tpl->block("BLOCK_PAGINACAO");

$tpl->TOTAL_PAGINAS = $configPaginacao['totalPaginas'];
$tpl->PAGINA_ANTERIOR = $configPaginacao['paginaAnterior'];
$tpl->PROXIMA_PAGINA = $configPaginacao['proximaPagina'];


foreach($rsUsuarios as $key => $Usuario){
	$tpl->ID = $Usuario->id;
	$tpl->NOME = $Usuario->nome;
	$tpl->PERFIL = $Usuario->perfil->descricao;
	$tpl->ID_HASH = $oUsuario->md5_encrypt($Usuario->id);
$tpl->block("BLOCK_ITEM_LISTA");	
}
include("tupi.template.finalizar.php"); 
?>