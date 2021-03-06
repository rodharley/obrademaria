<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 8;
include("tupi.seguranca.php");
$tpl->BREADCRUMB = '    <ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="#">Cadastro</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="cadastro.grupos.php">Grupos</a> <span class="divider">/</span>
    </li>

    <li class="active">Editar Grupo</li>
    </ul>';
//recuperacao do grupo
$oGrupo = new Grupo();
$oMoeda = new Moeda();
$oStatusGrupo = new StatusGrupo();
$tpl->ACAO = "Incluir";
$idMoedaAtual = 0;
$idEstatusAtual = 0;
if(isset($_REQUEST['idGrupo'])){
	$oGrupo->getById($oGrupo->md5_Decrypt($_REQUEST['idGrupo']));	
	$tpl->nomePacote = $oGrupo->nomePacote;
	$tpl->dataEmbarque = $oGrupo->convdata($oGrupo->dataEmbarque,'mtn');
	$tpl->dataChegada = $oGrupo->convdata($oGrupo->dataChegada,'mtn');
	$tpl->valorPacote = $oGrupo->money($oGrupo->valorPacote,'atb');
	$tpl->valorTaxaEmbarque = $oGrupo->money($oGrupo->valorTaxaEmbarque,'atb');
	$tpl->valorAdesao = $oGrupo->money($oGrupo->valorAdesao,'atb');
	$tpl->valorCusto = $oGrupo->money($oGrupo->valorCusto,'atb');
	$tpl->cotacaoCusto = $oGrupo->money($oGrupo->cotacaoCusto,'atb');
	$tpl->ano = $oGrupo->ano;
	$idMoedaAtual = $oGrupo->moeda->id;
	$idEstatusAtual = $oGrupo->status->id;
	//$tpl->possuiPacoteOpcional = $oGrupo->possuiPacoteOpcional;
	$tpl->nomePacoteOpcional = $oGrupo->nomePacoteOpcional;
	$tpl->valorPacoteOpcional = $oGrupo->money($oGrupo->valorPacoteOpcional,'atb');
	$tpl->valorTaxaEmbarqueOpcional = $oGrupo->money($oGrupo->valorTaxaEmbarqueOpcional,'atb');
	$tpl->valorAdesaoOpcional = $oGrupo->money($oGrupo->valorAdesaoOpcional,'atb');
	$tpl->valorCustoOpcional = $oGrupo->money($oGrupo->valorCustoOpcional,'atb');
	$tpl->roteiroAnexo = $oGrupo->roteiroAnexo;
	$tpl->pautaAnexo = $oGrupo->pautaAnexo;
	$tpl->plano = $oGrupo->plano;
	$tpl->destino = $oGrupo->destino;
	
	//contrato modelo
	if($oGrupo->modeloContrato == 'contrato1.php')
		$tpl->SELECTED_MODELO1 = 'selected';
	if($oGrupo->modeloContrato == 'contrato2.php')
		$tpl->SELECTED_MODELO2 = 'selected';
	if($oGrupo->modeloContrato == 'contrato3.php')
		$tpl->SELECTED_MODELO3 = 'selected';
	if($oGrupo->modeloContrato == 'contrato4.php')
        $tpl->SELECTED_MODELO4 = 'selected';
	
	//ficha modelo
	if($oGrupo->modeloFicha == 'fichaInscricao.php')
		$tpl->SELECTED_MODELO_FICHA1 = 'selected';
	if($oGrupo->modeloFicha == 'fichaInscricaoJMJ16.php')
		$tpl->SELECTED_MODELO_FICHA2 = 'selected';	
	
	//RECUPERA AS LOGS
	$oLog = new LogGrupo();
	$rslog = $oLog->getRows(0,999,array(),array("grupo"=>"=".$oGrupo->id));
	foreach($rslog as $key => $log){
		$tpl->DATA = $oGrupo->convdata(substr($log->dataHora,0,10),"mtn")." - ".substr($log->dataHora,10);
		$tpl->USUARIO = $log->usuario->nome;
		$tpl->LOG = $log->txtLog;
		$tpl->block('BLOCK_ITEM_LOG');
	}
	
$tpl->ACAO = "Alterar";
$tpl->ID = $oGrupo->id;
}


//moeda padrao
$rsMoeda = $oMoeda->getRows(0,999,array("descricao"=>"ASC"),array("padrao"=>"=1"));
foreach($rsMoeda as $key => $moeda){
$tpl->ID_MOEDA = $moeda->id;
$tpl->LABEL_MOEDA = $moeda->descricao;	
if($idMoedaAtual == $moeda->id){
	$tpl->SELECTED_MOEDA = "selected";	
}
$tpl->block("BLOCK_MOEDA");
$tpl->clear("SELECTED_MOEDA");	
}
//STATUS 
$rs = $oStatusGrupo->getRows();
foreach($rs as $key => $status){
$tpl->ID_STATUS = $status->id;
$tpl->LABEL_STATUS = $status->descricao;	
if($idEstatusAtual == $status->id){
	$tpl->SELECTED_STATUS = "selected";	
}
$tpl->block("BLOCK_STATUS");
$tpl->clear("SELECTED_STATUS");	
}


include("tupi.template.finalizar.php"); 
?>