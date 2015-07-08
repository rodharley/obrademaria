<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 13;
include("tupi.seguranca.php");
$tpl->BREADCRUMB = '    <ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="grupos.andamento.php">Grupos</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="participante.lista.php?idGrupo='.$_REQUEST['idGrupo'].'">Participantes</a> <span class="divider">/</span>
    </li>

    <li class="active">Editar Seguro e Tkt</li>
    </ul>';

$oCliente = new Cliente();
$oPartic = new Participante();
$oG = new Grupo();
$idGrupo = $oG->md5_decrypt($_REQUEST['idGrupo']);
$idParticipante = $oG->md5_decrypt($_REQUEST['idParticipante']);
$oG->getById($idGrupo);
$oPartic->getById($idParticipante);
$tpl->voucher = $oPartic->voucher;
$tpl->NOME_GRUPO = $oG->nomePacote;
$tpl->CPF = $oPartic->formataCPFCNPJ($oPartic->cliente->cpf);
$tpl->NOME = $oPartic->cliente->nomeCompleto;
$tpl->ID = $oPartic->id;
$tpl->ID_GRUPO_HASH = $_REQUEST['idGrupo'];
$tpl->ID_HASH = $_REQUEST['idParticipante'];
$tpl->ACAO = "EditarSeguro";


//RECUPERA OS VOOS
	$oVoo = new Voo();
	$oTicket = new Ticket();
	$rsVoo = $oVoo->getRows(0,999,array("id"=>"asc"),array("grupo"=>" = ".$idGrupo));
	foreach($rsVoo as $key => $voo){
	$tpl->NUMERO_VOO = $voo->numeroVoo;
	$tpl->DATA_VOO = $oVoo->convdata($voo->dataEmbarque,"mtn");
	$tpl->SAIDA_VOO = $voo->horaEmbarque;
	$tpl->CHEGADA_VOO = $voo->horaChegada;
	$tpl->TRECHO_VOO = $voo->trecho;
	$tpl->EMPRESA_VOO = $voo->companhiaAerea->descricao;
	$tpl->ID_VOO = $voo->id;
	if($oTicket->getRow(array("voo"=>" = ".$voo->id,"participante"=>" = ".$oPartic->id))){
	$tpl->TICKET_VOO = $oTicket->ticket;	
	$tpl->RESERVA_VOO = $oTicket->reserva;
	}else{
	$tpl->TICKET_VOO = "";	
	$tpl->RESERVA_VOO = "";
	}
		$tpl->block('BLOCK_ITEM_TICKET');
	}

include("tupi.template.finalizar.php"); 
?>