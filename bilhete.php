<?php

use Monolog\Handler\SwiftMailerHandler;

include("tupi.inicializar.php");
$codTemplate = 'tpl_shopping';
include("tupi.template.inicializar.php"); 


//CARREGA DADOS DO GRUPO 
if(isset($_REQUEST['charge_id'])){
    $oVenda = new VendaSite();
    $oGn = new GerenciaNetCheckOut();
    if($oVenda->getById($_REQUEST['charge_id'])){
        $vendaserenciaNet = $oGn->getByVendasId($oVenda->id);
    
    $tpl->CHARGE_ID = str_pad($oVenda->id,10,"0",STR_PAD_LEFT);
    $tpl->FORMA = $oVenda->printFormaPagamento();
    switch ($oVenda->formaPagamento) {
        case 'formaAVista':
            switch ($oVenda->tipoPagamento1) {
                case 'cheque':
                    $tpl->INFO_CHEQUE = $oVenda->printInfoCheque();
                    $tpl->block("BLOCK_CHEQUE_AVISTA");
                break;  
                case 'boleto':
                    $tpl->block("BLOCK_BOLETO_AVISTA");
                break;
                case 'transferencia':
                    $tpl->INFO_TRANSFERENCIA = $oVenda->printInfoCheque();
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
                    $tpl->INFO_TRANSFERENCIA = $oVenda->printInfoCheque();
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