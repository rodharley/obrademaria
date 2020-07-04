<?php
include("tupi.inicializar.php");
include("tupi.template.inicializar.php");
$codAcesso = 50;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '    <ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="grupos.andamento.php">Grupos</a> <span class="divider">/</span>
    </li>

    <li class="active">Lista de Participantes</li>
    </ul>';
}
//configura o grupo na pagina
$oGrupo = new Grupo();
$idGrupo = $oGrupo->md5_decrypt($_REQUEST['idGrupo']);
$oGrupo->getById($idGrupo);
$tpl->NOME_GRUPO = $oGrupo->nomePacote;
$tpl->ID_GRUPO_HASH = $_REQUEST['idGrupo'];

$oV = new VendaSite();
$oP = new Participante();
$oGn = new GerenciaNetCheckOut();
$totalVendas = $oV->recuperaTotal($idGrupo);
$pagina = isset($_REQUEST['pagina']) ? $_REQUEST['pagina'] : 1;
$configPaginacao = $oV->paginar($totalVendas,$pagina);
$rsvendas = $oV->pesquisa($configPaginacao['primeiroRegistro'],$configPaginacao['quantidadePorPagina'],$idGrupo);


if($configPaginacao['totalPaginas'] > 1){
	
$tpl->block("BLOCK_PAGINACAO");
}

$tpl->TOTAL_PAGINAS = $configPaginacao['totalPaginas'];
$tpl->PAGINA_ANTERIOR = $configPaginacao['paginaAnterior'];
$tpl->PROXIMA_PAGINA = $configPaginacao['proximaPagina'];
$tpl->PAGINA = $pagina;
foreach($rsvendas as $key => $venda){
	$tpl->RESERVA = $venda->printReserva();
	$tpl->ID_CLIENTE_HASH = $venda->md5_encrypt($venda->participante->cliente->id);

	$compras = $oGn->getByVendasId($venda->id);
	$charges = "";
	foreach ($compras as $key => $compra) {
		$charges .= $compra->charge_id." - ".$compra->status."<br/>";
	}
	$tpl->CHARGE_IDS = $charges;

	$participantes = $venda->participante->cliente->nomeCompleto;
	if($venda->acompanhante1 != null){
		$oP->getById($venda->acompanhante1);
		$participantes .= "<br/>".$oP->cliente->nomeCompleto;
	}
	if($venda->acompanhante2 != null){
		$oP->getById($venda->acompanhante2);
		$participantes .= "<br/>".$oP->cliente->nomeCompleto;
	}
	if($venda->acompanhante3 != null){
		$oP->getById($venda->acompanhante3);
		$participantes .= "<br/>".$oP->cliente->nomeCompleto;
	}
	if($venda->acompanhante4 != null){
		$oP->getById($venda->acompanhante4);
		$participantes .= "<br/>".$oP->cliente->nomeCompleto;
	}

	$tpl->PARTICIPANTE= $participantes;
	$tpl->FORMA = $venda->printFormaPagamento();
	$tpl->TIPO = $venda->tipoPagamento1."<br/>".$venda->tipoPagamento2;
$tpl->block("BLOCK_ITEM_LISTA");
}
include("tupi.template.finalizar.php");
?>