<?php
header("Content-Type: text/html; charset=iso-8859-1");
include("../../tupi.inicializar.php");
$obVenda = new VendaSite();
$obCheckout = new GerenciaNetCheckOut();
$obCielo = new MyCieloCheckout();
$obCliente = new Cliente();
$obGrupo = new Grupo();
$obCidade = new Cidade();
$obParticipante = new Participante();
$obPagamento = new Pagamento();
$obRoteiro = new Roteiro();
try{
//grupo
$obCheckout->conn->autocommit(false);
$obCheckout->conn->begin_transaction();

$_REQUEST = $obCheckout->utf8_decode_array($_REQUEST);

if(!$obGrupo->getById($_REQUEST['idGrupo'])){
    throw new Exception("Grupo não encontrado");
}

$dataHoje = Datetime::createFromFormat('Y-m-d',date("Y-m-d"));
$dataEmbarque = Datetime::createFromFormat('Y-m-d',$obGrupo->dataEmbarque);
$interval = $dataHoje->diff($dataEmbarque);
$meses = ($interval->y*12)+$interval->m;
if($obGrupo->bitCheque == 1 && $obGrupo->parcelaCheque != null && $obGrupo->parcelaCheque != '' && $obGrupo->parcelaCheque > 1 ){
    $mesesParcelaCheque = $meses < $obGrupo->parcelaCheque ? $meses : $obGrupo->parcelaCheque;
}else{
    $mesesParcelaCheque = 1;
}
if($obGrupo->bitCartao == 1 && $obGrupo->parcelaCartao != null && $obGrupo->parcelaCartao != '' && $obGrupo->parcelaCartao > 1 ){
    $mesesParcelaCartao =  $obGrupo->parcelaCartao;
}else{
    $mesesParcelaCartao = 1;
}

//incluir o cliente
$idCliente = $obCliente->saveBySite($_REQUEST);
if($obParticipante->getByIdCliente($idCliente,$obGrupo->id)){
    throw new Exception("Não é possível adquirir o pacote pois o cliente ".$obCliente->nomeCompleto." já está cadastrado neste pacote turístico");
}
//incluir como participante no grupo
$idParticipante  = $obParticipante->saveBySite($obGrupo,$obCliente,isset($_REQUEST['opcional'])?1:0);

//outros participantes
$idPartAcomp1 = 0;
$idPartAcomp2 = 0;
$idPartAcomp3 = 0;
$idPartAcomp4 = 0;
if($_REQUEST['quantidade'] > 1){
    if(isset($_REQUEST['acompanhante1'])){
        $obAcomp = new Cliente();
        $obParticipanteAcomp = new Participante();
        $idAcomp1 = $obAcomp->saveAcompanhanteBySite($_REQUEST['acompanhante1'],$_REQUEST['email1'],isset($_REQUEST['mailmarketing']) ? 1 : 0);
        
        if($obParticipante->getByIdCliente($idAcomp1,$obGrupo->id)){
            throw new Exception("Não é possível adquirir o pacote pois o cliente ".$obAcomp->nomeCompleto." com email: ".$obAcomp->email." já está cadastrado neste pacote turístico");
        }

        $idPartAcomp1  = $obParticipanteAcomp->saveBySite($obGrupo,$obAcomp,isset($_REQUEST['opcional'])?1:0);

    }
    if(isset($_REQUEST['acompanhante2'])){
        $obAcomp = new Cliente();
        $obParticipanteAcomp = new Participante();
        $idAcomp1 = $obAcomp->saveAcompanhanteBySite($_REQUEST['acompanhante2'],$_REQUEST['email2'],isset($_REQUEST['mailmarketing']) ? 1 : 0);
        
        if($obParticipante->getByIdCliente($idAcomp1,$obGrupo->id)){
            throw new Exception("Não é possível adquirir o pacote pois o cliente ".$obAcomp->nomeCompleto." com email: ".$obAcomp->email." já está cadastrado neste pacote turístico");
        }

        $idPartAcomp2  = $obParticipanteAcomp->saveBySite($obGrupo,$obAcomp,isset($_REQUEST['opcional'])?1:0);

    }
    if(isset($_REQUEST['acompanhante3'])){
        $obAcomp = new Cliente();
        $obParticipanteAcomp = new Participante();
        $idAcomp1 = $obAcomp->saveAcompanhanteBySite($_REQUEST['acompanhante3'],$_REQUEST['email3'],isset($_REQUEST['mailmarketing']) ? 1 : 0);

        if($obParticipante->getByIdCliente($idAcomp1,$obGrupo->id)){
            throw new Exception("Não é possível adquirir o pacote pois o cliente ".$obAcomp->nomeCompleto." com email: ".$obAcomp->email." já está cadastrado neste pacote turístico");
        }

        $idPartAcomp3  = $obParticipanteAcomp->saveBySite($obGrupo,$obAcomp,isset($_REQUEST['opcional'])?1:0);

    }
    if(isset($_REQUEST['acompanhante4'])){
        $obAcomp = new Cliente();
        $obParticipanteAcomp = new Participante();
        $idAcomp1 = $obAcomp->saveAcompanhanteBySite($_REQUEST['acompanhante4'],$_REQUEST['email4'],isset($_REQUEST['mailmarketing']) ? 1 : 0);

        if($obParticipante->getByIdCliente($idAcomp1,$obGrupo->id)){
            throw new Exception("Não é possível adquirir o pacote pois o cliente ".$obAcomp->nomeCompleto." com email: ".$obAcomp->email." já está cadastrado neste pacote turístico");
        }

        $idPartAcomp4  = $obParticipanteAcomp->saveBySite($obGrupo,$obAcomp,isset($_REQUEST['opcional'])?1:0);

    }
}

$formaPagamento = $_REQUEST['forma'];
switch($_REQUEST['forma']){
    case 'formaAVista':
        $tipoPagamento1 = $_REQUEST['pagamentoAVista'];
        $tipoPagamento2 = '';
    break;
    case 'formaEntrada':
        $tipoPagamento1 = $_REQUEST['entradaPagamentoEntrada'];
        $tipoPagamento2 = $_REQUEST['pagamentoEntrada'];   
    break;
    case 'formaParcelado':
        $tipoPagamento1 = $_REQUEST['pagamentoParcelado'];
        $tipoPagamento2 = '';
    break;
    case 'formaOutros':
        $tipoPagamento1 = 'Outros meios de pagamento';
        $tipoPagamento2 = '';
    break;
    }




$idVenda = $obVenda->createVenda($obParticipante,$obGrupo,isset($_REQUEST['opcional'])?1:0,$_REQUEST['quantidade'],$formaPagamento,$tipoPagamento1,$tipoPagamento2,$idPartAcomp1,$idPartAcomp2,$idPartAcomp3,$idPartAcomp4);

switch($_REQUEST['forma']){
 case 'formaOutros':
break;  
case 'formaAVista':
    $valorPagamento = $obVenda->total;
    if($_REQUEST['pagamentoAVista'] == 'transferencia'){
        $obVenda->incluirPagamentoSiteTransferencia($valorPagamento,"Pagamentos pela internet a vista");
    } else if($_REQUEST['pagamentoAVista'] == 'boleto'){
        $chargeBoleto = $obCheckout->createCharge($obParticipante,$obGrupo,$obVenda,$valorPagamento);
        $linkBoleto = $obCheckout->createLinkPagamento($chargeBoleto['data']['charge_id'],'','banking_billet');

    }elseif($_REQUEST['pagamentoAVista'] == 'cheque'){
        $obVenda->incluirPagamentoSiteCheque($valorPagamento,date("Y-m-d"),"Pagamentos pela internet a vista");
    }else{
        throw new Exception("Tipo de Pagamento não encontrado");
    }
break;
case 'formaEntrada':
    $valorEntrada = ($obVenda->total)*$_REQUEST['percentualEntrada'];
    $valorResto = ($obVenda->total)*(1-$_REQUEST['percentualEntrada']);
    
    if($_REQUEST['entradaPagamentoEntrada'] == 'transferencia'){
        $obVenda->incluirPagamentoSiteTransferencia($valorEntrada,"Pagamentos pela internet entrada");
    }elseif($_REQUEST['entradaPagamentoEntrada'] == 'boleto'){
        $chargeBoleto = $obCheckout->createCharge($obParticipante,$obGrupo,$obVenda,($valorEntrada/$obVenda->quantidade));
        $linkBoleto = $obCheckout->createLinkPagamento($chargeBoleto['data']['charge_id'],'','banking_billet');

    }elseif($_REQUEST['entradaPagamentoEntrada'] == 'cheque'){
        $obVenda->incluirPagamentoSiteCheque($valorEntrada, date("Y-m-d"),"Pagamentos pela internet entrada");
    }else{
        throw new Exception("Tipo de Pagamento não encontrado");
    }

    //parcelado
    if($_REQUEST['pagamentoEntrada'] == 'credit_card'){
        $valorParcela = $valorResto/$mesesParcelaCartao;
        //$obCheckout = new GerenciaNetCheckOut();
        //$chargeCartao = $obCheckout->createCharge($obParticipante,$obGrupo,$obVenda,$valorResto/$obVenda->quantidade);
        //$linkCartao = $obCheckout->createLinkPagamento($chargeCartao['data']['charge_id'],'','credit_card');
        $response = $obCielo->createLinkPagamento($obParticipante,$obGrupo,$obVenda,$valorResto/$obVenda->quantidade,$mesesParcelaCartao);
        $linkCartao = $response->settings->checkoutUrl;

    }elseif($_REQUEST['pagamentoEntrada'] == 'cheque'){
        $valorParcela = $valorResto/$mesesParcelaCheque;
        for($i=1;$i<=$mesesParcelaCheque;$i++){
            $dataAtual = DateTime::createFromFormat("Y-m-d",date("Y-m-d"));
            $dataAtual->add(new DateInterval("P".$i."M"));
            $obVenda->incluirPagamentoSiteCheque($valorParcela,$dataAtual->format("Y-m-d"),"Pagamentos pela internet parcelamento");
        }
    }else{
        throw new Exception("Tipo de Pagamento não encontrado");
    }

break;
case 'formaParcelado':
    if($_REQUEST['pagamentoParcelado'] == 'credit_card')
    {
        $valorParcela = $obVenda->total/$mesesParcelaCartao;
        //$obCheckout = new GerenciaNetCheckOut();
        //$chargeCartao = $obCheckout->createCharge($obParticipante,$obGrupo,$obVenda,$obVenda->total/$obVenda->quantidade);
        //$linkCartao = $obCheckout->createLinkPagamento($chargeCartao['data']['charge_id'],'','credit_card');
        $response = $obCielo->createLinkPagamento($obParticipante,$obGrupo,$obVenda,$obVenda->total/$obVenda->quantidade,$mesesParcelaCartao);
        $linkCartao = $response->settings->checkoutUrl;
    }elseif($_REQUEST['pagamentoParcelado'] == 'cheque'){
        $valorParcela = ($obVenda->total)/$mesesParcelaCheque;
        for($i=1;$i<=$mesesParcelaCheque;$i++){
            $dataAtual = DateTime::createFromFormat("Y-m-d",date("Y-m-d"));
            $dataAtual->add(new DateInterval("P".$i."M"));
            $obVenda->incluirPagamentoSiteCheque($valorParcela,$dataAtual->format("Y-m-d"),"Pagamentos pela internet parcelado");
        }
    }
    
break;
}


if($obCheckout->conn->commit()){

    //enviando email com dados da compra:
    $html = "Parabéns peregrino ".$_REQUEST['nomeCompleto'].", bem vindo à Obra de Maria DF!<br/><br/>";
    $html .= "Você está recebendo este email porque acabou de se inscrever em nosso roteiro de peregrinação : ".$obGrupo->nomePacote.".<br/><br/>";
    $html .= "Sua inscrição foi realizada com sucesso!</br>Para dar continuidade, clique no link abaixo para realizar o pagamento ou entre em contato conosco!<br/>";
    $html .= "<a href='".$obGrupo->urlSite."/bilhete.php?charge_id=".$obVenda->id."'>Acessar minha reserva</a>";


    //ROTEIRO 
    $roteiros = $obRoteiro->getByGrupo($obGrupo->id);
    if(count($roteiros) > 0){
    $html .= "<h4>Roteiro do seu pacote turístico:</h4>";
    $roteiro = $roteiros[0];
    foreach($roteiro->itineraryes as $key => $it){
        $html .= $it->order."-".$it->title."<br/>";
        $html .= $it->description."<br/><br/>";
    }
    }

    $tplemail = new Template("../../templates/tpl_email_ecommerce.html");
    $tplemail->CONTEUDO = $html;
    $obVenda->mail_html($_REQUEST['email'],$obVenda->REMETENTE, 'Obra de Maria DF', $tplemail->showString());


    //email para a obra de maria
    //enviando email com dados da compra:
    $html = "VENDA REALIADA PELO SITE  ".$_REQUEST['nomeCompleto']."-".$obCliente->cpf."<br/><br/>";
    $html .= "Grupo: ".$obGrupo->nomePacote.".<br/><br/>";
    $html .= "Forma de Pagamento: ".$obVenda->printFormaPagamento()."<br/><br/>";
    
    $tplemail = new Template("../../templates/tpl_email_ecommerce.html");
    $tplemail->CONTEUDO = $html;
    $obVenda->mail_html($obVenda->DESTINATARIO,$obVenda->REMETENTE, 'Obra de Maria DF', $tplemail->showString());
    $obCheckout->conn->begin_transaction();
    //gerar o contrato no mydocs do participante 1:
    $obParticipante->gerarContratoMyDocsSite();
    $obCheckout->conn->commit();
    echo json_encode(array("code"=>"200","data"=>array("charge_id"=>$idVenda)));
}else{
    echo json_encode(array("code"=>"500","data"=>array("mensagem"=>"Erro interno, tente novamente mais tarde")));
}

}catch(Exception $e){
    $mensagem = utf8_encode($e->getMessage());
    echo json_encode(array("code"=>"500","data"=>array("mensagem"=>"$mensagem")));
    $obCheckout->conn->rollback();
    exit();
}