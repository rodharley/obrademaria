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

    <li class="active">Lista de Participantes</li>
    </ul>';
}
//configura o grupo na pagina
$oGrupo = new Grupo();
$idGrupo = $oGrupo->md5_decrypt($_REQUEST['idGrupo']);
$oGrupo->getById($idGrupo);
$tpl->NOME_GRUPO = $oGrupo->nomePacote;
$tpl->ID_GRUPO_HASH = $_REQUEST['idGrupo'];

$oP = new Pagamento();
$oA = new Abatimento();
$oParticipante = new Participante();
$strBusca = isset($_REQUEST['busca']) ? str_replace(".","",str_replace("-","",$_REQUEST['busca'])) : "";
$totalParticipantes = $oParticipante->recuperaTotal($idGrupo,$strBusca);
$pagina = isset($_REQUEST['pagina']) ? $_REQUEST['pagina'] : 1;
$configPaginacao = $oParticipante->paginar($totalParticipantes,$pagina);
$rsPartic = $oParticipante->pesquisa($configPaginacao['primeiroRegistro'],$configPaginacao['quantidadePorPagina'],$idGrupo,$strBusca);
$tpl->MODELO_CONTRATO = $oGrupo->modeloContrato;
$tpl->MODELO_FICHA = $oGrupo->modeloFicha;
if($configPaginacao['totalPaginas'] > 1){
$tpl->block("BLOCK_PAGINACAO");
}

$tpl->TOTAL_PAGINAS = $configPaginacao['totalPaginas'];
$tpl->PAGINA_ANTERIOR = $configPaginacao['paginaAnterior'];
$tpl->PROXIMA_PAGINA = $configPaginacao['proximaPagina'];
$tpl->PAGINA = $pagina;
foreach($rsPartic as $key => $participante){
	//$tpl->ID = $participante->id;
	//calcula o status dos pagamentos;
	$tpl->STATUS_ABAT = 'status-ok';
	$rsPag = $oP->getRows(0,999,array("id"=>"asc"),array("participante"=>" = ".$participante->id,"cancelado"=>"=0"));
	foreach($rsPag as $key => $pagamento){
	$totalAbatMoedaGrupo = $oA->totalAbatimentos($pagamento->id);
	$totalAbatMoedaPagamento = $pagamento->CALCULA_MOEDA($totalAbatMoedaGrupo,$participante->grupo->moeda->id);
	if($totalAbatMoedaPagamento < $pagamento->valorPagamento && $pagamento->devolucao == 0)
	$tpl->STATUS_ABAT =  'status-alert';
	} 
	$tpl->CIFRAO = $oGrupo->moeda->cifrao;
	$tpl->PAGO = $oParticipante->money($participante->recuperaValorPago(),"atb")."/".$oParticipante->money($participante->valorTotal,"atb");
	$tpl->RESTA = "Pendêndia: ".$oGrupo->moeda->cifrao." ".$oParticipante->money(($participante->valorTotal-$participante->recuperaValorPago()),"atb");
	$tpl->DT_INSCR = $participante->convdata($participante->dataInscricao,"mtn");
	$tpl->NOME = $participante->id."-".$participante->cliente->nomeCompleto;
	$tpl->OPCIONAL = $participante->pacoteOpcional ? "Sim" : "Não";
	$tpl->ID_CLIENTE_HASH = $oParticipante->md5_encrypt($participante->cliente->id);
	$tpl->CPF = $oParticipante->formataCPFCNPJ($participante->cliente->cpf);
	$tpl->SITUACAO = $participante->status->descricao;
	$tpl->ID_HASH = $oParticipante->md5_encrypt($participante->id);
	
	if($participante->idcn == 0){
		$tpl->block("BLOCK_GERAR_CONTRATO_NUVEM");
	}else{
		$tpl->URL_CONTRATO = $oParticipante->endpointcn.'free/pdf/assinado/1/'.$participante->id.'.pdf';
		$tpl->block("BLOCK_CONTRATO_NUVEM");
	}
	if($participante->site == 1){
		$tpl->WEB = 'inline';
	}else{
		$tpl->WEB = 'none';
	}
	


	if($participante->status->id != $oParticipante->STATUS_DESISTENTE())
		$tpl->block("BLOCK_ACTIONS");
	else
		$tpl->block("BLOCK_REATIVAR");
$tpl->block("BLOCK_ITEM_LISTA");
}
include("tupi.template.finalizar.php");
?>