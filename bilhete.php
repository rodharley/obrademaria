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
    $oPagamento = new Pagamento();
    $oTp = new TipoPagamento();
    if($oVenda->getById($_REQUEST['charge_id'])){
    $oGrupo = $oVenda->participante->grupo;
    $vendaserenciaNet = $oGn->getByVendasId($oVenda->id);     
    $tpl->STATUS_COLOR = 'warning';
    $tpl->STATUS_NAME = 'EM ABERTO';

    //VERIFICA OS PAGAMENTOS ONLINE
    $pagNaoPagos = $oPagamento->getPagamentosParticipanteNaoPagos($oVenda->participante->id);
    if(count($pagNaoPagos) == 0){        
        $pagNaoPagos = $oGn->getByVendasNaoPagasId($oVenda->id);        
        if(count($pagNaoPagos) == 0){                   
            $tpl->STATUS_COLOR = 'success';
            $tpl->STATUS_NAME = 'PAGO';
        }       
    }

    $tpl->GRUPO_NOME = $oGrupo->nomePacote;
    $tpl->GRUPO_MOEDA = $oGrupo->moeda->cifrao;
    $desconto = 0;
    if($oVenda->desconto > 0){
        if($oVenda->opcional){
        $desconto = ($oGrupo->valorPacote+$oGrupo->valorPacoteOpcional)*($oVenda->desconto/100);
        }else{
        $desconto = ($oGrupo->valorPacote)*($oVenda->desconto/100);    
        }

    }
    $tpl->GRUPO_VALOR_CURRENCY = $oGrupo->money($oGrupo->valorPacote,"atb");
    $tpl->GRUPO_VALOR_ADESAO_CURRENCY =  $oGrupo->money($oGrupo->valorAdesao,"atb");
    $tpl->GRUPO_VALOR_EMBARQUE_CURRENCY =  $oGrupo->money($oGrupo->valorTaxaEmbarque,"atb");
    if($oVenda->opcional ==1){
        $tpl->GRUPO_OPCIONAL_NOME = $oGrupo->nomePacoteOpcional;
        $tpl->GRUPO_OPCIONAL_VALOR_CURRENCY = $oGrupo->money($oGrupo->valorPacoteOpcional,"atb");
        $tpl->GRUPO_OPCIONAL_VALOR_ADESAO_CURRENCY = $oGrupo->money($oGrupo->valorAdesaoOpcional,"atb");
        $tpl->GRUPO_OPCIONAL_VALOR_ADESAO_CURRENCY = $oGrupo->money($oGrupo->valorTaxaEmbarqueOpcional,"atb");
        $tpl->block('BLOCK_OPCIONAL');
        $tpl->block("BLOCK_OPCIONAL_NOME");
    }
    $tpl->COTACAO = $oGn->money($oVenda->cotacao,"atb");
    $tpl->QUANTIDADE = $oVenda->quantidade;
    $tpl->VALOR_TOTAL = $oGn->money($oVenda->total,"atb");
    $tpl->DESCONTO = $oGrupo->money($desconto,"atb");
    
     
    //dados dos participantes e grupo
    $tpl->DATA_EMBARQUE = $oGrupo->convdata($oGrupo->dataEmbarque,"mtn");
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
                            $tpl->ENTRADA = $oVenda->money($charge->total,"atb");
                        }                      
                        
                    }
                    $tpl->block("BLOCK_BOLETO_ENTRADA");
                    
                    break;
                case 'cheque':
                    $tpl->INFO_CHEQUE = $oVenda->printInfoCheque();
                    $rsPag = $oPagamento->getValorPagamentosPorTipoeParticipante($oVenda->participante->id,$oTp->CHEQUE(),'entrada');
                    $total = $oVenda->DAO_GerarArray($rsPag);
                    $tpl->ENTRADA = $oVenda->money($total['total'],"atb");
                    
                    $tpl->block("BLOCK_CHEQUE_ENTRADA");
                    break;
                case 'transferencia':
                    $tpl->INFO_TRANSFERENCIA = $oVenda->printInfoTransferencia();
                    $rsPag = $oPagamento->getValorPagamentosPorTipoeParticipante($oVenda->participante->id,$oTp->BANCO(),'entrada');
                    $total = $oVenda->DAO_GerarArray($rsPag);
                    $tpl->ENTRADA = $oVenda->money($total['total'],"atb");
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
            switch ($oVenda->tipoPagamento1) {
                case 'cheque':
                    $tpl->INFO_CHEQUE = $oVenda->printInfoCheque();
                    $tpl->block("BLOCK_PARCELADO");
                break;
                case 'credit_card':
                    foreach ($vendaserenciaNet as $key => $charge) {                        
                        if($charge->payment_method == 'credit_card'){
                            $tpl->URLGN_CARTAO = $charge->payment_url;                           
                        }                        
                    }
                    $tpl->block("BLOCK_CARTAO_PARCELADO");
                break;
            }
            
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