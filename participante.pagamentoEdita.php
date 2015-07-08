<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 13;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
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
	<li>
    <a href="participante.pagamentos.php?idGrupo='.$_REQUEST['idGrupo'].'&idParticipante='.$_REQUEST['idParticipante'].'">Pagamentos</a> <span class="divider">/</span>
    </li>
    <li class="active">Edita Pagamento</li>
    </ul>';
}
//configura o grupo na pagina
$oGrupo = new Grupo();
$oPagamento = new Pagamento();
$idGrupo = $oGrupo->md5_decrypt($_REQUEST['idGrupo']);
$oGrupo->getById($idGrupo);
$tpl->NOME_GRUPO = $oGrupo->nomePacote;
$tpl->ID_GRUPO = $_REQUEST['idGrupo'];
$tpl->ID_PARTICIPANTE = $_REQUEST['idParticipante'];
$tpl->ID_PARTICIPANTE_REP = $oGrupo->md5_decrypt($_REQUEST['idParticipante']);
$oPartic = new Participante();
$oPartic->getById($oPartic->md5_decrypt($_REQUEST['idParticipante']));
$tpl->NOME_PARTICIPANTE = $oPartic->cliente->nomeCompleto;
$tpl->ACAO = 'IncluirPagamento';
$tpl->DEV = $_REQUEST['dev'];
$otp = new TipoPagamento();
$rs = $otp->getRows();
$ofin = new FinalidadePagamento();
$rsf = $ofin->getRows();

$idtp = 0;
$idFinalidade = 0;
$tpl->ABAT_AUTO_CHECKED = 'checked="checked"';
//carrega dados do pagamento
if(isset($_REQUEST['idPagamento'])){
$tpl->ACAO = 'AlterarPagamento';

$oPagamento->getById($oPagamento->md5_decrypt($_REQUEST['idPagamento']));
$idtp = $oPagamento->tipo->id;
$idFinalidade = $oPagamento->finalidade->id;
$tpl->DATA_PAGAMENTO = $oPagamento->convdata($oPagamento->dataPagamento,"mtn");
$tpl->OBS = $oPagamento->obs;
$tpl->ABAT_AUTO_CHECKED = $oPagamento->abatimentoAutomatico == 1 ? 'checked="checked"' : "";
$tpl->ID_TIPO = $idtp;
$tpl->ID = $oPagamento->id;
$tpl->DEV = $oPagamento->devolucao;
$tpl->block("BLOCK_CARREGA_PAGAMENTO");
}

if($tpl->DEV == 0)
$tpl->DESC_TIPO_TRANSACAO = 'Pagamento';
else
$tpl->DESC_TIPO_TRANSACAO = 'Devolução';
//tipo de pagamento
foreach($rs as $key => $tp){
$tpl->ID_TIPOPAG = $tp->id;
$tpl->LABEL_TIPOPAG = $tp->descricao;
if($idtp == $tp->id){
	$tpl->SELECTED_TIPOPAG = "selected";
}
$tpl->block("BLOCK_TIPOPAG");
$tpl->clear("SELECTED_TIPOPAG");
}

//finalidade
foreach($rsf as $key => $f){
$tpl->ID_FINALIDADE = $f->id;
$tpl->LABEL_FINALIDADE = $f->descricao;
if($idFinalidade == $f->id){
	$tpl->SELECTED_FINALIDADE = "selected";
}
$tpl->block("BLOCK_FINALIDADE");
$tpl->clear("SELECTED_FINALIDADE");
}

include("tupi.template.finalizar.php"); 
?>