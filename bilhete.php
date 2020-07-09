<?php

use Monolog\Handler\SwiftMailerHandler;

include("tupi.inicializar.php");
$codTemplate = 'tpl_shopping';
include("tupi.template.inicializar.php"); 


//CARREGA DADOS DO GRUPO 
if(isset($_REQUEST['charge_id'])){
    $oVenda = new VendaSite();
    $oGrupo = new Grupo();
    $oGn = new GerenciaNetCheckOut();
    $oparticipante = new Participante();
    if($oVenda->getById($_REQUEST['charge_id'])){
    $oGrupo = $oVenda->participante->grupo;
    $tpl->GRUPO_VALOR_PACOTE_CURRENCY = $oGrupo->money($oGrupo->valorPacote,"atb");
    $tpl->VALOR_TOTAL = $oGn->money($oVenda->total,"atb");
    $tpl->GRUPO_NOME = $oGrupo->nomePacote;
    $tpl->COTACAO = $oGn->money($oVenda->cotacao,"atb");
    $tpl->GRUPO_MOEDA = $oGrupo->moeda->cifrao;
    $tpl->TOTAL_DOLLAR = $oGn->money($oVenda->total/$oVenda->cotacao,"atb");
    $tpl->GRUPO_VALOR_ADESAO_CURRENCY =  $oGrupo->money($oGrupo->valorAdesao,"atb");
    $tpl->GRUPO_VALOR_EMBARQUE_CURRENCY =  $oGrupo->money($oGrupo->valorTaxaEmbarque,"atb");
    $tpl->DESCONTO ="0,00";
    if($oVenda->opcional ==1){
        $tpl->GRUPO_OPCIONAL_NOME = $oGrupo->nomePacoteOpcional;
        $tpl->GRUPO_OPCIONAL_VALOR_CURRENCY = $oGrupo->money($oGrupo->getValorTotalOpcional(),"atb");
        $tpl->block('BLOCK_OPCIONAL');
        $tpl->block("BLOCK_OPCIONAL_NOME");
    }
    $tpl->DATA_EMBARQUE = $oGrupo->convdata($oGrupo->dataEmbarque,"mtn");
    $tpl->QUANTIDADE = $oVenda->quantidade;


    $tpl->NOME_PARTICIPANTE = $oVenda->participante->cliente->nomeCompleto;
    $tpl->block("BLOCK_PARTICIPANTE");
    if($oVenda->acompanhante1 != null){
        $oparticipante->getById($oVenda->acompanhante1);
        $tpl->NOME_PARTICIPANTE = $oparticipante->cliente->nomeCompleto;
        $tpl->block("BLOCK_PARTICIPANTE");
    }
    if($oVenda->acompanhante2 != null){
        $oparticipante->getById($oVenda->acompanhante2);
        $tpl->NOME_PARTICIPANTE = $oparticipante->cliente->nomeCompleto;
        $tpl->block("BLOCK_PARTICIPANTE");
    }
    if($oVenda->acompanhante3 != null){
        $oparticipante->getById($oVenda->acompanhante3);
        $tpl->NOME_PARTICIPANTE = $oparticipante->cliente->nomeCompleto;
        $tpl->block("BLOCK_PARTICIPANTE");
    }
    if($oVenda->acompanhante4 != null){
        $oparticipante->getById($oVenda->acompanhante4);
        $tpl->NOME_PARTICIPANTE = $oparticipante->cliente->nomeCompleto;
        $tpl->block("BLOCK_PARTICIPANTE");
    }
    $vendaserenciaNet = $oGn->getByVendasId($oVenda->id);    
    $tpl->CHARGE_ID = str_pad($oVenda->id,10,"0",STR_PAD_LEFT);
    $tpl->FORMA = $oVenda->printFormaPagamento();
    switch ($oVenda->formaPagamento) {
        case 'formaAVista':
            $tpl->DESCONTO = $oGn->money($oVenda->total*0.05,"atb");
            switch ($oVenda->tipoPagamento1) {
                case 'cheque':
                    $tpl->INFO_CHEQUE = $oVenda->printInfoCheque();
                    $tpl->block("BLOCK_CHEQUE_AVISTA");
                break;  
                case 'boleto':
                    $tpl->block("BLOCK_BOLETO_AVISTA");
                break;
                case 'transferencia':
                    $tpl->INFO_TRANSFERENCIA = $oVenda->printInfoTransferencia();
                    $tpl->block("BLOCK_TRANSFERENCIA_AVISTA");
                break;
            }
            $tpl->block("BLOCK_AVISTA");
        break;
        case 'formaEntrada':
            switch ($oVenda->tipoPagamento1) {
                case 'boleto':
                    foreach ($vendaserenciaNet as $key => $charge) {
        
                        if($charge->payment_method == 'banking_billet'){
                            $tpl->URLGN_BOLETO = $charge->payment_url;
                            
                        }                      
                        
                    }
                    $tpl->block("BLOCK_BOLETO_ENTRADA");
                    
                    break;
                case 'cheque':
                    $tpl->INFO_CHEQUE = $oVenda->printInfoCheque();
                    $tpl->block("BLOCK_CHEQUE_ENTRADA");
                    break;
                case 'transferencia':
                    $tpl->INFO_TRANSFERENCIA = $oVenda->printInfoTransferencia();
                    $tpl->block("BLOCK_TRANSFERENCIA_ENTRADA");
                    break;                
            }


            switch ($oVenda->tipoPagamento2) {
                case 'cheque':
                    $tpl->INFO_CHEQUE = $oVenda->printInfoCheque();
                    $tpl->block("BLOCK_CHEQUE_ENTRADA_RESTO");
                break;
                case 'credit_card':
                    foreach ($vendaserenciaNet as $key => $charge) {                        
                        if($charge->payment_method == 'credit_card'){
                            $tpl->URLGN_CARTAO = $charge->payment_url;                           
                        }                        
                    }
                    $tpl->block("BLOCK_CARTAO_ENTRADA_RESTO");
                break;
            }
            $tpl->block("BLOCK_ENTRADA");
        break;
        case 'formaParcelado':
            $tpl->INFO_CHEQUE = $oVenda->printInfoCheque();
            $tpl->block("BLOCK_PARCELADO");
        break;
        default:
            # code...
        break;
    }
/*
    foreach ($vendaserenciaNet as $key => $charge) {
        
        if($charge->payment_method == 'banking_billet'){
            $tpl->URLGN_BOLETO = $charge->payment_url;
            $tpl->block("BLOCK_BOLETO"); 
        }
        if($charge->payment_method == 'credit_card'){
            $tpl->URLGN_CARTAO = $charge->payment_url;
            $tpl->block("BLOCK_CARTAO"); 
        }
        
    }
    */

       
    $tpl->block("COMPRA_VALIDA");
    }else{
        $tpl->block("COMPRA_INVALIDA");
    }
}
include("tupi.template.finalizar.php"); 