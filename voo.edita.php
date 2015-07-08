<?php
include("tupi.inicializar.php");
include("tupi.template.inicializar.php");
$codAcesso = 11;
include("tupi.seguranca.php");
$tpl->BREADCRUMB = '    <ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="grupos.andamento.php">Grupos</a> <span class="divider">/</span>
    </li>
	 <li>
    <a href="voo.lista.php?idGrupo='.$_REQUEST['idGrupo'].'">Vôos</a> <span class="divider">/</span>
    </li>
    <li class="active">Edita Vôo</li>
    </ul>';
//recuperacao do cliente
//configura o grupo na pagina
$oGrupo = new Grupo();
$idGrupo = $oGrupo->md5_decrypt($_REQUEST['idGrupo']);
$oGrupo->getById($idGrupo);
$tpl->NOME_GRUPO = $oGrupo->nomePacote;


$oVoo = new Voo();
$tpl->ACAO = "Incluir";
$idCompanhia = 0;
$oCA = new CompanhiaAerea();
$rsCA = $oCA->getRows(0,999,array("descricao"=>"asc"),array());
$tpl->ID_GRUPO = $_REQUEST['idGrupo'];
if(isset($_REQUEST['idVoo'])){
$oVoo->getById($oVoo->md5_Decrypt($_REQUEST['idVoo']));
//ids dos relacionamentos
$idCompanhia = $oVoo->companhiaAerea->id;

$tpl->NUMERO = $oVoo->numeroVoo;
$tpl->DATA = $oVoo->convdata($oVoo->dataEmbarque,"mtn");
$tpl->HORA = $oVoo->horaEmbarque;
$tpl->HORA_CHEGADA = $oVoo->horaChegada;
$tpl->TRECHO = $oVoo->trecho;
$tpl->ACAO = "Alterar";
$tpl->ID = $oVoo->id;
}


//estado civil
foreach($rsCA as $key => $ca){
$tpl->ID_CA = $ca->id;
$tpl->LABEL_CA = $ca->descricao;
if($idCompanhia == $ca->id){
	$tpl->SELECTED_CA = "selected";
}
$tpl->block("BLOCK_CA");
$tpl->clear("SELECTED_CA");
}
include("tupi.template.finalizar.php");
?>