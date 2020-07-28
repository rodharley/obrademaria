<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 10;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '<ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="#">Grupos</a> <span class="divider">/</span>
    </li>
	    <li>
    <a href="#">Participantes</a> <span class="divider">/</span>
    </li>
    <li class="active">Editar Contrato</li>
    </ul>';
}
//configura o grupo na pagina
$oGrupo = new Grupo();
$oParticipante = new Participante();

$idGrupo = $oGrupo->md5_decrypt($_REQUEST['idGrupo']);
$idPartic = $oGrupo->md5_decrypt($_REQUEST['idParticipante']);
$oGrupo->getById($idGrupo);
$oParticipante->getById($idPartic);
$tpl->NOME_GRUPO = $oGrupo->nomePacote;
$tpl->ID_GRUPO = $_REQUEST['idGrupo'];
$tpl->ID_PARTICIPANTE = $_REQUEST['idParticipante'];
$tpl->ACAO = 'SalvaContrato';

if($oParticipante->contrato != ""){
$tpl->CONTRATO = $oParticipante->contrato;
}else{
$tpl->addFile("CONTRATO","templates/".str_replace(".php",".html",$oGrupo->modeloContrato));
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
if($oParticipante->pacoteOpcional == 1){
$tpl->taxaAdesao = $oParticipante->money($oGrupo->valorAdesao + $oGrupo->valorAdesaoOpcional,"atb");
$tpl->total = $oParticipante->money($oGrupo->valorPacote+$oGrupo->valorTaxaEmbarque+$oGrupo->valorPacoteOpcional+$oGrupo->valorTaxaEmbarqueOpcional,"atb");
}else{
$tpl->taxaAdesao = $oParticipante->money($oGrupo->valorAdesao,"atb");
$tpl->total = $oParticipante->money($oGrupo->valorPacote+$oGrupo->valorTaxaEmbarque,"atb");
}
$tpl->totalPassagem ="0";
$tpl->block("BLOCK_PADRAO");
}
include("tupi.template.finalizar.php"); 
?>