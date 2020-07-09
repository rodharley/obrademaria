<?php 

include("tupi.inicializar.php");
$codTemplate = 'tpl_shopping';
include("tupi.template.inicializar.php"); 


//CARREGA DADOS DO GRUPO 
if(isset($_REQUEST['idGrupo'])){
    $oGrupo = new Grupo();
    $oAg = new Agendamento();
    $oAg->getById(6);

    $oGrupo->getById($_REQUEST['idGrupo']);	
    $dataHoje = Datetime::createFromFormat('Y-m-d',date("Y-m-d"));
    $dataEmbarque = Datetime::createFromFormat('Y-m-d',$oGrupo->dataEmbarque);
    $interval = $dataHoje->diff($dataEmbarque);
    $meses = $interval->format('%m');
    //pega as cotacoes
    $tpl->PARCELAMAXIMA = $meses;
    $tpl->COTACAO = $oGrupo->cotacaoAVista;
    $tpl->COTACAO_ENTRADA = $oGrupo->cotacaoEntrada;
    $tpl->COTACAO_PARCELADO = $oGrupo->cotacaoParcelado;
    $tpl->COTACAO_CURRENCY = $oGrupo->money($oGrupo->cotacaoAVista,"atb");
    $tpl->COTACAO_ENTRADA_CURRENCY = $oGrupo->money($oGrupo->cotacaoEntrada,"atb");
    $tpl->COTACAO_PARCELADO_CURRENCY = $oGrupo->money($oGrupo->cotacaoParcelado,"atb");

    $tpl->URL_IMAGE_GRUPO = 'img/grupos/'.$oGrupo->imagemDestaque;
    $tpl->GRUPO_NOME = $oGrupo->nomePacote;
    $tpl->QUANTIDADE = 1;
    $tpl->GRUPO_IDMOEDA = $oGrupo->moeda->id;
    if( $oGrupo->moeda->id == 2){
        $tpl->DISPLAY_DOLLAR = 'd-none';
    }
    $tpl->GRUPO_VALOR = $oGrupo->getValorTotal(0);
    $tpl->GRUPO_VALOR_CURRENCY = $oGrupo->money($oGrupo->valorPacote,"atb");
    $tpl->GRUPO_VALOR_ADESAO_CURRENCY = $oGrupo->money($oGrupo->valorAdesao,"atb");
    $tpl->GRUPO_VALOR_EMBARQUE_CURRENCY = $oGrupo->money($oGrupo->valorTaxaEmbarque,"atb");
    $tpl->ID_GRUPO = $oGrupo->id;
    if($oGrupo->possuiPacoteOpcional ==1){
        $tpl->GRUPO_OPCIONAL_NOME = $oGrupo->nomePacoteOpcional;
        $tpl->GRUPO_OPCIONAL_VALOR = $oGrupo->getValorTotalOpcional();
        $tpl->GRUPO_OPCIONAL_VALOR_CURRENCY = $oGrupo->money($oGrupo->getValorTotalOpcional(),"atb");
        $tpl->block('BLOCK_OPCIONAL');

    }else{
        $tpl->GRUPO_OPCIONAL_VALOR = 0;
    }
    $tpl->GRUPO_MOEDA = $oGrupo->moeda->cifrao;
    $tpl->GRUPO_OPCIONAL = $oGrupo->possuiPacoteOpcional;
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
$contrato = str_replace(",<strong>##estado_civil##</strong>","",$contrato);
$tpl->CONTRATO =   str_replace("da Cédula de Identidade n°<strong>##rg## - ##rgOrgaoExpedidor##</strong>, e","",$contrato);
            

    //endereco
    $ouf = new Uf();

    $ufs = $ouf->getRows(0,99,array("uf"=>'asc'));
    foreach ($ufs as $key => $uf) {
        $tpl->UF = $uf->uf;
        $tpl->block("BLOCK_ESTADO");
        # code...
    }
    
}
include("tupi.template.finalizar.php"); 