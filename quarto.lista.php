<?php
include("tupi.inicializar.php");
include("tupi.template.inicializar.php");
$codAcesso = 12;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '    <ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="grupos.andamento.php">Grupos</a> <span class="divider">/</span>
    </li>

    <li class="active">Lista de Quartos</li>
    </ul>';
}
//configura o grupo na pagina
//configura o grupo na pagina
$oGrupo = new Grupo();
$idGrupo = $oGrupo->md5_decrypt($_REQUEST['idGrupo']);
$oGrupo->getById($idGrupo);
$tpl->NOME_GRUPO = $oGrupo->nomePacote;
$tpl->ID_GRUPO_HASH = $_REQUEST['idGrupo'];


$oquarto = new quarto();
$totalquartos = $oquarto->recuperaTotal($idGrupo);
$pagina = isset($_REQUEST['pagina']) ? $_REQUEST['pagina'] : 1;
$configPaginacao = $oquarto->paginar($totalquartos,$pagina);
$rsquarto = $oquarto->getRows($configPaginacao['primeiroRegistro'],$configPaginacao['quantidadePorPagina'],array("id"=>"asc"),array("grupo"=>" = ".$idGrupo));

if($configPaginacao['totalPaginas'] > 1){
$tpl->block("BLOCK_PAGINACAO");
}

$tpl->TOTAL_PAGINAS = $configPaginacao['totalPaginas'];
$tpl->PAGINA_ANTERIOR = $configPaginacao['paginaAnterior'];
$tpl->PROXIMA_PAGINA = $configPaginacao['proximaPagina'];
$tpl->PAGINA = $pagina;


foreach($rsquarto as $key => $quarto){
	$tpl->ID = $quarto->id;
	$tpl->NUMERO = $quarto->numero;
	$tpl->CAPACIDADE = $quarto->capacidade;
	$tpl->ID_HASH = $oquarto->md5_encrypt($quarto->id);
	$tpl->block("BLOCK_ITEM_LISTA");
}
include("tupi.template.finalizar.php");
?>