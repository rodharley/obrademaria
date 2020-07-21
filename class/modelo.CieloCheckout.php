<?php
use CieloCheckout\Order;
use CieloCheckout\Item;
use CieloCheckout\Discount;
use CieloCheckout\Cart;
use CieloCheckout\Address;
use CieloCheckout\Services;
use CieloCheckout\Shipping;
use CieloCheckout\Payment;
use CieloCheckout\Customer;
use CieloCheckout\Options;
use CieloCheckout\Transaction;
use Cielo\Merchant;

class MyCieloCheckout extends Persistencia {
    var $checkoutUrl;
    var $profile;
    var $version;
    var $participante = NULL;
    var $venda = NULL;
    var $amount;
    var $created_date;
    var $order_number;
    var $checkout_cielo_order_number;
    var $payment_method_type;
    var $payment_method_brand;
    var $payment_method_bank;
    var $payment_maskedcreditcard;
    var $payment_installments;
    var $payment_status;
    var $tid;
    var $test_transaction;
    

    public function getByChargeId($chargeId){
        return $this->getRow(array("checkout_cielo_order_number"=>"=".$chargeId));
    }
    
    public function getByVendasId($vendaId){
        return $this->getRows(0,4,array(),array("venda"=>"=".$vendaId));
    }
    
    public function getByVendasNaoPagasId($vendaId){
        return $this->getRows(0,4,array(),array("venda"=>"=".$vendaId,"payment_status"=>"!=2"));
    }

    function createLinkPagamento($obParticipante,$obGrupo,$obVenda,$valor){
        try {
            if($obVenda->opcional)
                $nomeItem = $obGrupo->nomePacote."+".$obGrupo->nomePacoteOpcional;
                else
                $nomeItem = $obGrupo->nomePacote;
            // Instantiate cart's item object and set it to an array of product items.
            $properties = [
              'Name' => substr($nomeItem,0,128),
              'Description' => substr($obGrupo->destino,0,256),
              'UnitPrice' => intval(str_replace(".","",str_replace(",","",$this->money($valor,"atb")))),
              'Quantity' => intval($obVenda->quantidade),
              'Type' => 'Service',
              'Sku' => '',
              'Weight' => 0,
            ];
            $Items = [
              new Item($properties),
            ];
            
            // Instantiate cart discount object.
            $properties = [
              'Type' => 'Percent',
              'Value' => 0,
            ];
            $Discount = new Discount($properties);
            
            // Instantiate shipping address' object.
            $properties = [
              'Street' => $obParticipante->cliente->endereco,
              'Number' => "000",
              'Complement' => '',
              'District' => $obParticipante->cliente->endereco,
              'City' => $obParticipante->cliente->cidadeEndereco,
              'State' => $obParticipante->cliente->estadoEndereco,
            ];
            $Address = new Address($properties);
            
            // Instantiate shipping services' object.
            //$properties = [
            //  'Name' => 'Serviço de frete',
            //  'Price' => 123,
            //  'DeadLine' => 15,
            //];
            
            $Services = [
              //new Services($properties),
            ];
            
            // Instantiate shipping's object.
            $properties = [
              'Type' => 'WithoutShipping',
              'SourceZipCode' => '70000000',
              'TargetZipCode' => str_pad($obParticipante->cliente->cep,8,"0",STR_PAD_RIGHT),
              'Address' => $Address,
              'Services' => $Services,
            ];
            $Shipping = new Shipping($properties);
            
            // Instantiate payment's object.
            $properties = [
              'BoletoDiscount' => 0,
              'DebitDiscount' => 0,
            ];
            $Payment = new Payment($properties);
            
            // Instantiate customer's object.
            $properties = [
              'Identity' => $obParticipante->cliente->cpf,
              'FullName' => $obParticipante->cliente->nomeCompleto,
              'Email' => $obParticipante->cliente->email,
              'Phone' => substr($this->limpaDigitos($obParticipante->cliente->celular != "" ? $obParticipante->cliente->celular : $obParticipante->cliente->telefoneResidencial),0,11),
            ];
            $Customer = new Customer($properties);
            
            // Instantiate options' object.
            $properties = [
              'AntifraudEnabled' => FALSE,
              'ReturnUrl' => $this->urlSite."bilhete.php?charge_id=".$obVenda->id,
            ];
            $Options = new Options($properties);
            
            // Instantiate order's object.
            $properties = [
              'OrderNumber' => $obVenda->id,
              'SoftDescriptor' => 'RAINHATOUR',
              // Instantiate cart's object.
              'Cart' => new Cart(['Discount' => $Discount, 'Items' => $Items]),
              'Shipping' => $Shipping,
              'Payment' => $Payment,
              'Customer' => $Customer,
              'Options' => $Options,
            ];
            $Order = new Order($properties);
            
            $headers = array('Accept' => 'application/json','MerchantId'=>$this->cieloClientID,'Content-Type'=>'application/json; charset=utf-8');
		        $query = Unirest\Request\Body::json($Order);
            $response = Unirest\Request::post($this->endpointCielo.'/orders', $headers, $query);
            if($response->code != 200 && $response->code != 201){
                if(isset($response->body)){
                throw new Exception($response->body->message);
                }else{
                    throw new Exception("Erro inesperado!");    
                }
            }

            if(isset($response->body->message)){
                throw new Exception($response->body->message);
            }

            $Transaction = $response->body;
            // Instantiate merchant's object.
            //$Merchant = new Merchant($this->cieloClientID, $this->cieloClientSecret);
            
            // Instantiate transaction's object.
            //$Transaction = new Transaction($Merchant, $Order);
            //$Transaction->request_new_transaction(true);
            

            $this->venda = $obVenda;
            $this->participante = $obParticipante;
            $this->order_number = $obVenda->id;	
            $this->payment_status  = 1;
            $this->amount = $this->money($valor,"bta");           
            $this->created_date = date("Y-m-d H:i:s");
            $this->checkoutUrl =  $Transaction->settings->checkoutUrl;
            $this->profile =  $Transaction->settings->profile;
            $this->version =  $Transaction->settings->version;
            $this->save();
            return $Transaction;
            //print_r($Transaction->response);
            
            // This will throw an exception when running from terminal cli.
            //$Transaction->redirect_to_cielo();
          }
          catch(Exception $e) {
            throw $e;
          }
    }


  function UpdateByNotification($url,$idVenda,$logger){     

   try {
    $vendas = $this->getByVendasId($idVenda);
    if(count($vendas) > 0){
      $this->getById($vendas[0]->id);
    $headers = array('Accept' => 'application/json','MerchantId'=>$this->cieloClientID,'Content-Type'=>'application/json; charset=utf-8');
		        //$query = Unirest\Request\Body::json($Order);
            $response = Unirest\Request::get($this->endpointCielo.'/orders/'.$this->cieloClientID.'/'.$idVenda, $headers);
            $logger->info($response->code);
            if($response->code != 200 && $response->code != 201){
                if(isset($response->body)){
                throw new Exception($response->body->message);
                }else{
                    throw new Exception("Erro inesperado!");    
                }
            }

            if(isset($response->body->message)){
                throw new Exception($response->body->message);
            }

            $order = $response->body;
           
      /*{
    "order_number": "Pedido01",
    "amount": 101,
    "discount_amount": 0,
    "checkout_cielo_order_number": "65930e7460bd4a849502ed14d7be6c03",
    "created_date": "12-09-2017 14:38:56",
    "customer_name": "Test Test",
    "customer_phone": "21987654321",
    "customer_identity": "84261300206",
    "customer_email": "test@cielo.com.br",
    "shipping_type": 1,
    "shipping_name": "Motoboy",
    "shipping_price": 1,
    "shipping_address_zipcode": "21911130",
    "shipping_address_district": "Freguesia",
    "shipping_address_city": "Rio de Janeiro",
    "shipping_address_state": "RJ",
    "shipping_address_line1": "Rua Cambui",
    "shipping_address_line2": "Apto 201",
    "shipping_address_number": "92",
    "payment_method_type": 1,
    "payment_method_brand": 1,
    "payment_maskedcreditcard": "471612******7044",
    "payment_installments": 1,
    "payment_status": 3,
    "tid": "10447480686J51OH8BPB",
    "test_transaction": "False"
}
*/
   //tratar status do charge
   switch($order->payment_status){
       case '2':

          $this->checkout_cielo_order_number = $order->checkout_cielo_order_number;
          $this->payment_status = $order->payment_status;
          $this->payment_method_type = $order->payment_method_type;
          $this->payment_method_brand = $order->payment_method_brand;
          $this->payment_maskedcreditcard =$order->payment_maskedcreditcard;
          $this->payment_installments = $order->payment_installments;
          $this->tid = $order->tid;
          $this->test_transaction = $order->test_transaction;
          $this->save();
           $this->gerarPagamentos($order->amount);
           return 'pagamento gerado com suscesso';
       break;
       case '1':
           
       break;
       case '3':
        $this->checkout_cielo_order_number = $order->checkout_cielo_order_number;
        $this->payment_status = $order->payment_status;
        $this->payment_method_type = $order->payment_method_type;
        $this->payment_method_brand = $order->payment_method_brand;
        $this->payment_maskedcreditcard =$order->payment_maskedcreditcard;
        $this->payment_installments = $order->payment_installments;
        $this->tid = $order->tid;
        $this->test_transaction = $order->test_transaction;
        $this->save();        
         return 'pagamento foi rejeitado!';
       break;
       case '4':
       break;
   }
   
  }else{
    return "não foi executado nenhum procedimento";
  }

   
   
   
   
       } catch (Exception $e) {
           throw $e;
       }
   }




   private function gerarPagamentos($valorPago){
    $qtd = 1;
    if($this->venda->acompanhante1 != null)
        $qtd++;
    if($this->venda->acompanhante2 != null)
        $qtd++;
    if($this->venda->acompanhante3 != null)
        $qtd++;
    if($this->venda->acompanhante4 != null)
        $qtd++;
    
    
    
    //converte para decimal o valor
    $valor = $this->convertvalorGerenciaNet($valorPago,'gtd')/$qtd;
    
    

    for($i=1;$i<=$qtd;$i++){
        $part = new Participante();
        $oTipoP = new TipoPagamento();
        $oFin = new FinalidadePagamento();  
        $pag = new Pagamento();          
        $om = new Moeda();
        $oTipoP->id = $oTipoP->GERENCIA_NET();
        $oFin->id = 1;
        $om->id = $om->REAL();
        if($i==1){
            $part->getById($this->venda->participante->id);
        }else if($i == 2){
            $part->getById($this->venda->acompanhante1);
        }else if($i == 3){
            $part->getById($this->venda->acompanhante2);
        }else if($i == 4){
            $part->getById($this->venda->acompanhante3);
        }else if($i == 5){
            $part->getById($this->venda->acompanhante4);
        }
        
        $pag->dataPagamento = date("Y-m-d");
        $pag->valorPagamento = $this->money($valor,"bta");
        $pag->obs = 'pagamento automático vindo da gerencianet';
        $pag->abatimentoAutomatico =1;
        $pag->moeda = $om;
        $pag->participante = $this->venda->participante;
      $pag->tipo = $oTipoP;
        $pag->finalidade = $oFin;
        $pag->cancelado = 0;
      $pag->devolucao = 0;
        $pag->valorParcela = 0;
        $pag->cotacaoMoedaReal=0;
    $pag->cotacaoReal = $this->venda->cotacao;
        $pag->parcela = 1;
        $pag->site = 1;
      $pag->pago = 1;
        $pag->save();
        $oAbat = new Abatimento();	
        $oG = new Grupo();
    $oG->getById($this->venda->participante->grupo->id);
        if($oG->moeda->id == $om->DOLLAR()){
            $oAbat->valor = $pag->CALCULA_DOLLAR();
        }else{
            $oAbat->valor = $pag->CALCULA_REAL();
        }	
        $oAbat->participante = $part;
        $oAbat->pagamento = $pag;
        $oAbat->save();
        
      $part->atualiza_status();
    }
    
    
}
}