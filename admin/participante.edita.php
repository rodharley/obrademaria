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

    <li class="active">Editar Participante</li>
    </ul>';

$oCliente = new Cliente();
$oPartic = new Participante();
$oG = new Grupo();
$idGrupo = $oG->md5_decrypt($_REQUEST['idGrupo']);
$idParticipante = $oG->md5_decrypt($_REQUEST['idParticipante']);
$oG->getById($idGrupo);
$oPartic->getById($idParticipante);
$tpl->SELECTED_OPCIONAL_SIM = $oPartic->pacoteOpcional ? "selected" : "";
$tpl->SELECTED_OPCIONAL_NAO = $oPartic->pacoteOpcional ? "" : "selected";

$tpl->CIFRAO = $oG->moeda->cifrao;
$tpl->NOME_GRUPO = $oG->nomePacote;
$tpl->CPF = $oPartic->formataCPFCNPJ($oPartic->cliente->cpf);
$tpl->NOME = $oPartic->cliente->nomeCompleto;
$tpl->custoTotal = $oPartic->money($oPartic->custoTotal,"atb");
$tpl->valorTotal = $oPartic->money($oPartic->valorTotal,"atb");
$tpl->dt_inscr = $oPartic->convdata($oPartic->dataInscricao,"mtn");
$tpl->ID = $oPartic->id;
$tpl->ID_GRUPO_HASH = $_REQUEST['idGrupo'];
$tpl->ACAO = "Editar";

if(strpos($_SESSION['ag_itensMenu'],",8") !== false)
$tpl->block("BLOCK_EDICAO");
else
$tpl->block("BLOCK_NAOEDICAO");


//RECUPERA AS LOGS
	$oLog = new LogParticipante();
	$rslog = $oLog->getRows(0,999,array(),array("participante"=>"=".$idParticipante));
	foreach($rslog as $key => $log){
		$tpl->DATA = $oPartic->convdata(substr($log->dataHora,0,10),"mtn")." - ".substr($log->dataHora,10);
		$tpl->USUARIO = $log->usuario->nome;
		$tpl->VALOR = $oPartic->money($log->valor,"atb");
		$tpl->CUSTO = $oPartic->money($log->custo,"atb");
		$tpl->block('BLOCK_ITEM_LOG');
	}

include("tupi.template.finalizar.php"); 
?>