<?php 
include("tupi.inicializar.php"); 
$codTemplate = "tpl_seguro";
include("tupi.template.inicializar.php"); 
$codAcesso = 10;
include("tupi.seguranca.php");
//configura o grupo na pagina
$oParticipante = new Participante();
$oCidade = new Cidade();
$oGrupo = new Grupo();
$idGrupo = $oGrupo->md5_decrypt($_REQUEST['idGrupo']);
$oGrupo->getById($idGrupo);
$oParticipante->getById( $oGrupo->md5_decrypt($_REQUEST['idParticipante']));
$cliente = $oParticipante->cliente;

$tpl->NOME = $oParticipante->nomeFamilia();
$tpl->VOUCHER = $oParticipante->voucher;
$tpl->PLANO = $oGrupo->plano;
$tpl->INI_VIG = $oGrupo->convdata($oGrupo->dataEmbarque,"mtn");
$tpl->FIM_VIG = $oGrupo->convdata($oGrupo->dataChegada,"mtn");
$datetime1 = date_create($oGrupo->dataEmbarque);
$datetime2 = date_create($oGrupo->dataChegada);
$interval = date_diff($datetime1, $datetime2);
$tpl->DIAS_VIG = $interval->format('%a')+1;
$tpl->DESTINO = $oGrupo->destino;

$tpl->PASSAGEIRO = $oParticipante->nomeFamilia();
$tpl->SEXO = $oParticipante->cliente->sexo;
$tpl->DOCUMENTO = $oParticipante->cliente->passaporte;

$imgsCAS = "";
$imgsId ="5";
//RECUPERA OS VOOS
	$oVoo = new Voo();
	$oTicket = new Ticket();
	$rsVoo = $oVoo->getRows(0,999,array("id"=>"asc"),array("grupo"=>" = ".$idGrupo));
	foreach($rsVoo as $key => $voo){
	$tpl->VOO = $voo->numeroVoo;
	$tpl->DATA = $oVoo->convdata($voo->dataEmbarque,"mtn");
	$tpl->SAIDA = $voo->horaEmbarque;
	$tpl->CHEGADA = $voo->horaChegada;
	$tpl->TRECHO = $voo->trecho;
	if($voo->companhiaAerea->logomarca != "" && !strpos($imgsId,'.'.$voo->companhiaAerea->id.'.')){
	$tpl->LOGOMARCA = $voo->companhiaAerea->logomarca;
	$tpl->block('BLOCK_LOGO_OP');
	$imgsId .= '.'.$voo->companhiaAerea->id.'.';
	}

	if($oTicket->getRow(array("voo"=>" = ".$voo->id,"participante"=>" = ".$oParticipante->id))){
	$tpl->TICKET = $oTicket->ticket;	
	$tpl->RESERVA = $oTicket->reserva;
	}else{
	$tpl->TICKET = "";	
	$tpl->RESERVA = "";
	}
		$tpl->block('BLOCK_ITEM_LISTA');
	}



include("tupi.template.finalizar.php"); 
?>