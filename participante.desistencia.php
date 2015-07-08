<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 13;
include("tupi.seguranca.php");
$oMoeda = new Moeda();
$oPagamento = new Pagamento();
$oParticipante = new Participante();
$idParticipante = $oParticipante->md5_decrypt($_REQUEST['idParticipante']);
$oParticipante->getById($idParticipante);
$nomeGrupo = $oParticipante->grupo->nomePacote;
$idGrupo = $oParticipante->md5_encrypt($oParticipante->grupo->id);
$nomePartic =  $oParticipante->cliente->nomeCompleto;
$valorPagamento = $oParticipante->recuperaValorTodosPagamentos($oParticipante->grupo->moeda->id);
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '    <ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="grupos.andamento.php">Grupos</a> <span class="divider">/</span>
    </li>
	<li>
    <a href="participante.lista.php?idGrupo='.$idGrupo.'">Participantes</a> <span class="divider">/</span>
    </li>
	<li class="active">Cancelar Participante</li>
    </ul>';
}
$tpl->NOME_GRUPO = $nomeGrupo;
$tpl->NOME_PARTICIPANTE = $nomePartic;
$tpl->MOEDA_GRUPO = $oParticipante->grupo->moeda->descricao;
$tpl->CIFRAO_GRUPO = $oParticipante->grupo->moeda->cifrao;
$tpl->VALOR_PAGO = $oParticipante->grupo->moeda->cifrao." ".$oParticipante->money($valorPagamento,"atb");
$tpl->ID_PARTICIPANTE = $_REQUEST['idParticipante'];
$tpl->ID_GRUPO = $idGrupo;


$tpl->ACAO = 'Cancelar';

//carrega dados do pagamento
include("tupi.template.finalizar.php"); 
?>