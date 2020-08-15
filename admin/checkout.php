<?php 

include("tupi.inicializar.php");
$codTemplate = 'tpl_shopping';
include("tupi.template.inicializar.php"); 


//CARREGA DADOS DO GRUPO 
if(isset($_REQUEST['idGrupo'])){
    $oGrupo = new Grupo();
    $oAg = new Agendamento();
    $oec = new EstadoCivil();
    $oAg->getById(6);

    $oGrupo->getById($_REQUEST['idGrupo']);	
    $dataHoje = Datetime::createFromFormat('Y-m-d',date("Y-m-d"));
    $dataEmbarque = Datetime::createFromFormat('Y-m-d',$oGrupo->dataEmbarque);
    
    $interval = $dataHoje->diff($dataEmbarque);
    $meses = ($interval->y*12)+$interval->m;

    //pega as cotacoes
    
    $tpl->COTACAO = $oGrupo->cotacaoAVista;
    $tpl->COTACAO_ENTRADA = $oGrupo->cotacaoEntrada;
    $tpl->COTACAO_PARCELADO = $oGrupo->cotacaoParcelado;
    $tpl->COTACAO_CURRENCY = $oGrupo->money($oGrupo->cotacaoAVista,"atb");
    $tpl->COTACAO_ENTRADA_CURRENCY = $oGrupo->money($oGrupo->cotacaoEntrada,"atb");
    $tpl->COTACAO_PARCELADO_CURRENCY = $oGrupo->money($oGrupo->cotacaoParcelado,"atb");
    $tpl->DESCONTO_A_VISTA = $oGrupo->descontoAVista;
    $tpl->TEXT_DESCONTO_A_VISTA = $oGrupo->descontoAVista != 0 ? '('.$oGrupo->descontoAVista.'% de Desconto)' : '';
    $tpl->BACKGROUND = 'img/grupos/'.$oGrupo->imagemDestaque;
    $tpl->GRUPO_NOME = $oGrupo->nomePacote;
    $tpl->QUANTIDADE = 1;
    $tpl->GRUPO_IDMOEDA = $oGrupo->moeda->id;
    if( $oGrupo->moeda->id == 2){
        $tpl->DISPLAY_DOLLAR = 'd-none';
    }
    $tpl->GRUPO_VALOR = $oGrupo->valorPacote;
    $tpl->GRUPO_VALOR_CURRENCY = $oGrupo->money($oGrupo->valorPacote,"atb");
    $tpl->GRUPO_VALOR_ADESAO_CURRENCY = $oGrupo->money($oGrupo->valorAdesao,"atb");
    $tpl->GRUPO_VALOR_ADESAO = $oGrupo->valorAdesao;
    $tpl->GRUPO_VALOR_EMBARQUE_CURRENCY = $oGrupo->money($oGrupo->valorTaxaEmbarque,"atb");
    $tpl->GRUPO_VALOR_EMBARQUE = $oGrupo->valorTaxaEmbarque;
    $tpl->ID_GRUPO = $oGrupo->id;
    if($oGrupo->possuiPacoteOpcional ==1){
        $tpl->GRUPO_OPCIONAL_NOME = $oGrupo->nomePacoteOpcional;
        $tpl->GRUPO_OPCIONAL_VALOR = $oGrupo->valorPacoteOpcional;
        $tpl->GRUPO_OPCIONAL_VALOR_CURRENCY = $oGrupo->money($oGrupo->valorPacoteOpcional,"atb");
        $tpl->GRUPO_OPCIONAL_VALOR_ADESAO = $oGrupo->valorAdesaoOpcional;
        $tpl->GRUPO_OPCIONAL_VALOR_ADESAO_CURRENCY = $oGrupo->money($oGrupo->valorAdesaoOpcional,"atb");
        $tpl->GRUPO_OPCIONAL_VALOR_EMBARQUE= $oGrupo->valorTaxaEmbarqueOpcional;
        $tpl->GRUPO_OPCIONAL_VALOR_EMBARQUE_CURRENCY = $oGrupo->money($oGrupo->valorTaxaEmbarqueOpcional,"atb");
        $tpl->block('BLOCK_OPCIONAL');

    }else{
        $tpl->GRUPO_OPCIONAL_VALOR = 0;
        $tpl->GRUPO_OPCIONAL_VALOR_ADESAO = 0;
        $tpl->GRUPO_OPCIONAL_VALOR_EMBARQUE = 0;
    }
    $tpl->GRUPO_MOEDA = $oGrupo->moeda->cifrao;
    $tpl->GRUPO_OPCIONAL = $oGrupo->possuiPacoteOpcional;
//forma de pagamento customizada
$avista = 0;
$parcelado = 0;
if($oGrupo->bitBoleto == 1 || $oGrupo->bitCheque == 1 || $oGrupo->bitTransferencia == 1 || $oGrupo->bitCartao == 1){
    $tpl->FORMA_AVISTA_CHECKED = 'checked="checked"';
    $tpl->block("BLOCK_FORMA_AVISTA");
    $avista = 1;
}
if($oGrupo->bitCartao == 1 || $oGrupo->bitCheque == 1){
    if($avista == 0){
    $tpl->FORMA_ENTRADA_CHECKED = 'checked="checked"';
    }
    $tpl->block("BLOCK_FORMA_ENTRADA");
    if($oGrupo->bitCheque == 1){
        $tpl->block("BLOCK_FORMA_PARCELADO");
    }
    $parcelado = 1;
}

if($oGrupo->bitCustomizado == 1){
    if($avista == 0 && $parcelado == 0){
        $tpl->FORMA_CUSTOMIZADA_CHECKED = 'checked="checked"';
    }
    $tpl->NOME_CUSTOMIZADA = $oGrupo->nomeCustomizado;
    $tpl->TEXT_CUSTOMIZADO = $oGrupo->textCustomizado;
    $tpl->block("BLOCK_FORMA_CUSTOMIZADA");
}
if($oGrupo->bitBoleto == 1){
    $tpl->block('BLOCK_BOLETO_AVISTA');
    $tpl->block('BLOCK_BOLETO_ENTRADA');
 
}
if($oGrupo->bitCheque == 1){
    
    $tpl->PARCELA_CHEQUE_MAXIMA = $oGrupo->parcelaCheque != null && $oGrupo->parcelaCheque != '' ? $meses < $oGrupo->parcelaCheque ? $meses : $oGrupo->parcelaCheque : 1;
    $tpl->block('BLOCK_CHEQUE_AVISTA');
    $tpl->block('BLOCK_CHEQUE_ENTRADA');
    $tpl->block('BLOCK_CHEQUE_ENTRADA_PARCELA');
    $tpl->block('BLOCK_CHEQUE_PARCELADO');    
}else{
    $tpl->PARCELA_CHEQUE_MAXIMA = 1;
}
if($oGrupo->bitCartao == 1){
    $tpl->PARCELA_CARTAO_MAXIMA = $oGrupo->parcelaCartao != null && $oGrupo->parcelaCartao != '' ? $oGrupo->parcelaCartao : 1;
    $tpl->block('BLOCK_CARTAO_ENTRADA_PARCELA');
    $tpl->block('BLOCK_CARTAO_AVISTA');
}else{
    $tpl->PARCELA_CARTAO_MAXIMA = 1;
}




//montando os contratos

    switch($oGrupo->modeloContrato){
        case 'contrato1.php':
            $tpl2 = new Template("templates/contrato1.html");
            
        break;
        case 'contrato2.php':
            $tpl2 = new Template("templates/contrato2.html");
        break;
        case 'contrato3.php':
            $tpl2 = new Template("templates/contrato3.html");
        break;
        case 'contrato4.php':
            $tpl2 = new Template("templates/contrato4.html");
        break;

    }

$tpl2->CIFRAO = $oGrupo->moeda->cifrao;
$tpl2->nomeCompleto = "##nomeCompleto##";
$tpl2->nacionalidade = "##nacionalidade##";
$tpl2->estado_civil = "##estado_civil##";
$tpl2->rg = "##rg##";
$tpl2->rgOrgaoExpedidor = "##rgOrgaoExpedidor##";
$tpl2->cpf = "##cpf##";
$tpl2->endereco = "##endereco##";
$tpl2->cidade = "##cidade##";
$tpl2->uf = "##uf##";;
$tpl2->taxaAdesao =$oGrupo->money($oGrupo->valorAdesao,"atb");
$tpl2->total = "##total##";
$tpl2->block("BLOCK_PADRAO");
$contrato = $tpl2->showString();
$tpl->CONTRATO = str_replace("BRASILIA-DF,_____ de ______________________ de __________","BRASILIA-DF,".Date("d")." de ".$oGrupo->mesExtenso(Date("n"))." de ".date("Y").",",$contrato);
//$tpl->CONTRATO =   str_replace("da Cédula de Identidade n°<strong>##rg## - ##rgOrgaoExpedidor##</strong>, e","",$contrato);
            

    //endereco
    $ouf = new Uf();

    $ufs = $ouf->getRows(0,99,array("uf"=>'asc'));
    foreach ($ufs as $key => $uf) {
        $tpl->UF = $uf->uf;
        $tpl->block("BLOCK_ESTADO");
    }

    $rsec = $oec->getRows(0,99,array('descricao'=>'ASC'));
    foreach ($rsec as $key => $ec) {
        $tpl->ID_ESTADO_CIVIL = $ec->id;
        $tpl->DESC_ESTADO_CIVIL = $ec->descricao;
        $tpl->block("BLOCK_ESTADO_CIVIL");
    }
    
}
include("tupi.template.finalizar.php"); 