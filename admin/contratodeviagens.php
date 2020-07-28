<?php 
include("tupi.inicializar.php"); 
$codTemplate = "contrato";
include("tupi.template.inicializar.php"); 
$codAcesso = 10;
include("tupi.seguranca.php");
//configura o grupo na pagina
$oParticipante = new Participante();
$oCidade = new Cidade();
$oGrupo = new Grupo();
$idGrupo = $oGrupo->md5_decrypt($_REQUEST['idGrupo']);
$oGrupo->getById($idGrupo);
$tpl->ID_GRUPO_HASH = $_REQUEST['idGrupo'];
$tpl->ID_PARTICIPANTE_HASH = $_REQUEST['idParticipante'];
$oParticipante->getById( $oGrupo->md5_decrypt($_REQUEST['idParticipante']));
$cliente = $oParticipante->cliente;
$tpl->CIFRAO = $oGrupo->moeda->cifrao;
$tpl->nomeCompleto = $cliente->nomeCompleto;
$tpl->nacionalidade = $cliente->nacionalidade;
$tpl->estado_civil = $cliente->estadoCivil->descricao;
$tpl->rg = $cliente->rg;
$tpl->rgOrgaoExpedidor = $cliente->orgaoEmissorRg;
$tpl->cpf = $cliente->cpf;
$tpl->endereco = $cliente->endereco;
$tpl->cidade = $cliente->cidadeEndereco;
$tpl->uf = $cliente->estadoEndereco;
//$tsinscricao = strtotime($oParticipante->dataInscricao);
//$tpl->dia = date("d",$tsinscricao);
//$tpl->mes = $oParticipante->mesExtenso(date("m",$tsinscricao));
//$tpl->ano = date("Y",$tsinscricao);

 
if($oParticipante->pacoteOpcional == 1){
$tpl->taxaAdesao = $oParticipante->money($oGrupo->valorAdesao + $oGrupo->valorAdesaoOpcional,"atb");
//$tpl->valorPacote = $oParticipante->money($oGrupo->valorPacote+$oGrupo->valorTaxaEmbarque+$oGrupo->valorPacoteOpcional+$oGrupo->valorTaxaEmbarqueOpcional,"atb");
$tpl->total = $oParticipante->money($oGrupo->valorPacote+$oGrupo->valorTaxaEmbarque+$oGrupo->valorPacoteOpcional+$oGrupo->valorTaxaEmbarqueOpcional,"atb");
}else{
$tpl->taxaAdesao = $oParticipante->money($oGrupo->valorAdesao,"atb");
//$tpl->valorPacote = $oParticipante->money($oGrupo->valorPacote+$oGrupo->valorTaxaEmbarque,"atb");
$tpl->total = $oParticipante->money($oGrupo->valorPacote+$oGrupo->valorTaxaEmbarque,"atb");
}
$tpl->totalPassagem = $_REQUEST['valorPassagem'];
if(!isset($_REQUEST['tupiSendEmail']))
$tpl->block("BLOCK_ENVIO_EMAIL");
include("tupi.template.finalizar.php"); 
?>