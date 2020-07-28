<?php
include("tupi.inicializar.php");
include("tupi.template.inicializar.php");
$codAcesso = 11;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '    <ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="grupos.andamento.php">Grupos</a> <span class="divider">/</span>
    </li>

    <li class="active">Lista de Vôos</li>
    </ul>';
}
//configura o grupo na pagina
//configura o grupo na pagina
$oGrupo = new Grupo();
$idGrupo = $oGrupo->md5_decrypt($_REQUEST['idGrupo']);
$oGrupo->getById($idGrupo);
$tpl->NOME_GRUPO = $oGrupo->nomePacote;
$tpl->ID_GRUPO_HASH = $_REQUEST['idGrupo'];


$oVoo = new Voo();
$totalVoos = $oVoo->recuperaTotal($idGrupo);
$pagina = isset($_REQUEST['pagina']) ? $_REQUEST['pagina'] : 1;
$configPaginacao = $oVoo->paginar($totalVoos,$pagina);
$rsVoo = $oVoo->getRows($configPaginacao['primeiroRegistro'],$configPaginacao['quantidadePorPagina'],array("id"=>"asc"),array("grupo"=>" = ".$idGrupo));

if($configPaginacao['totalPaginas'] > 1){
$tpl->block("BLOCK_PAGINACAO");
}

$tpl->TOTAL_PAGINAS = $configPaginacao['totalPaginas'];
$tpl->PAGINA_ANTERIOR = $configPaginacao['paginaAnterior'];
$tpl->PROXIMA_PAGINA = $configPaginacao['proximaPagina'];
$tpl->PAGINA = $pagina;


foreach($rsVoo as $key => $voo){
	$tpl->ID = $voo->id;
	$tpl->NUMERO = $voo->numeroVoo;
	$tpl->DATA = $oVoo->convdata($voo->dataEmbarque,"mtn");
	$tpl->HORA = $voo->horaEmbarque;
	$tpl->HORA_CHEGADA = $voo->horaChegada;
	$tpl->TRECHO = $voo->trecho;
	$tpl->COMPANHIA = $voo->companhiaAerea->descricao;
	$tpl->ID_HASH = $oVoo->md5_encrypt($voo->id);
	$tpl->block("BLOCK_ITEM_LISTA");
}
include("tupi.template.finalizar.php");
?>