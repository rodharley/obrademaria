<?php 
use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;


class GerenciaNetCheckOut extends Persistencia {
    var $id = NULL;
	var $charge_id;
	var $status;	
	var $message = NULL;
    var $participante = NULL;
    var  $venda = NULL;
    var $total;
    var $created_at;
    var $payment_method;
    var $update_at;
    var $payment_url;
    var $token;
    


public function getByChargeId($chargeId){
    return $this->getRow(array("charge_id"=>"=".$chargeId));
}

public function getByVendasId($vendaId){
    return $this->getRows(0,4,array(),array("venda"=>"=".$vendaId));
}

public function getByVendasNaoPagasId($vendaId){
    return $this->getRows(0,4,array(),array("venda"=>"=".$vendaId,"status"=>"!='paid'"));
}

function createLinkPagamento($charge_id,$mensagem,$tipoPagamento){
       
// $charge_id refere-se ao ID da transa��o gerada anteriormente
$params = [
  'id' => $charge_id
];

$date = new DateTime(date("Y-m-d"));
$date->add(new DateInterval('P3D'));

$body = [
  'message' => utf8_encode($mensagem), // mensagem para o pagador com at� 80 caracteres
  'expire_at' => $date->format('Y-m-d') , // data de vencimento da tela de pagamento e do pr�prio boleto
  'request_delivery_address' => false, // solicitar endere�o de entrega do comprador?
  'payment_method' => $tipoPagamento // formas de pagamento dispon�veis
];

try {
    $api = new Gerencianet($this->getOptions());
  $response = $api->linkCharge($params, $body);
  $this->payment_method = $response['data']['payment_method'];
  $this->payment_url = $response['data']['payment_url'];
  $this->status = $response['data']['status'];
  $this->update_at = date("Y-m-d H:i:s");
  $this->save();
  return $response;
} catch (GerencianetException $e) {
    throw $e;
} catch (Exception $e) {
    throw $e;
}


    }

    function createCharge($obParticipante,$obGrupo,$obVenda,$valor){
       


        if($obVenda->opcional)
        $nomeItem = $obGrupo->nomePacote."+".$obGrupo->nomePacoteOpcional;
        else
        $nomeItem = $obGrupo->nomePacote;
        $item_1 = [
            'name' => utf8_encode($nomeItem), // nome do item, produto ou servi�o
            'amount' => intval($obVenda->quantidade), // quantidade
            'value' => intval(str_replace(".","",str_replace(",","",$this->money($valor,"atb")))) // valor (1000 = R$ 10,00) (Obs: � poss�vel a cria��o de itens com valores negativos. Por�m, o valor total da fatura deve ser superior ao valor m�nimo para gera��o de transa��es.)
        ];
         
        $items =  [
            $item_1
        ];
        $metadata = [
            "custom_id"=> strval($obVenda->id),
            "notification_url"=>$this->urlScripts."chargegn.php"
        ];
        
        // Exemplo para receber notifica��es da altera��o do status da transa��o.
        // $metadata = ['notification_url'=>'sua_url_de_notificacao_.com.br']
        // Outros detalhes em: https://dev.gerencianet.com.br/docs/notificacoes
        
        // Como enviar seu $body com o $metadata
        // $body  =  [
        //    'items' => $items,
        //    'metadata' => $metadata
        // ];
        
        $body  =  [
            'items' => $items,
            'metadata' => $metadata
        ];
        
        try {
            $api = new Gerencianet($this->getOptions());
            $charge = $api->createCharge([], $body);
            
            $this->venda = $obVenda;
            $this->participante = $obParticipante;
            $this->charge_id = $charge['data']['charge_id'];	
            $this->status  = "new";
            $this->message = "Cria��o do ChargeId";
            $this->total = $this->money($valor,"bta");           
            $this->created_at = date("Y-m-d H:i:s");
            $this->save();
            return $charge;
        } catch (GerencianetException $e) {
           
            throw $e;
        } catch (Exception $e) {
            
            throw $e;
        }
    }

    function getOptions(){
        $clientId = $this->clientIdGN; // insira seu Client_Id, conforme o ambiente (Des ou Prod)
        $clientSecret = $this->clientSecretGN; // insira seu Client_Secret, conforme o ambiente (Des ou Prod)
         
        $options = [
          'client_id' => $clientId,
          'client_secret' => $clientSecret,
          'sandbox' => true // altere conforme o ambiente (true = desenvolvimento e false = producao)
        ];
        return $options;
    }

    function UpdateByNotification(){

       /*
        * Este token ser� recebido em sua vari�vel que representa os par�metros do POST
        * Ex.: $_POST['notification']
        */
        $token = $_POST["notification"];
        
        $params = [
        'token' => $token
        ];
 
    try {
        $api = new Gerencianet($this->getOptions());
        $chargeNotification = $api->getNotification($params, []);
    // Para identificar o status atual da sua transa��o voc� dever� contar o n�mero de situa��es contidas no array, pois a �ltima posi��o guarda sempre o �ltimo status. Veja na um modelo de respostas na se��o "Exemplos de respostas" abaixo.
    
    // Veja abaixo como acessar o ID e a String referente ao �ltimo status da transa��o.
        
        // Conta o tamanho do array data (que armazena o resultado)
        $i = count($chargeNotification["data"]);
        // Pega o �ltimo Object chargeStatus
        $ultimoStatus = $chargeNotification["data"][$i-1];
        // Acessando o array Status
        $status = $ultimoStatus["status"];
        // Obtendo o ID da transa��o    
        $charge_id = $ultimoStatus["identifiers"]["charge_id"];
        
        // Obtendo a String do status atual
    $statusAtual = $status["current"];
    
    //atualiza o pagamento
    $this->getByChargeId($charge_id);
    $this->status =$statusAtual;
    $this->token = $token;
    $this->save();
    //tratar status do charge
    switch($statusAtual){
        case 'paid':
            $this->gerarPagamentos($ultimoStatus["value"]);
            $html = "Parab�ns peregrino ".$this->participante->nomeCompleto.", Seu Pagamento de R$ ".$this->money($this->total,"atb")." foi aprovado!<br/><br/>";
          $html .= "O Valor ser� adicionado a sua reserva no roteiro de peregrina��o : ".$this->participante->grupo->nomePacote.".<br/><br/>";
          $html .= "Para consultar sua reserva, clique no link abaixo ou entre em contato conosco!<br/>";
          $html .= "<a href='".$this->urlSite."/bilhete.php?charge_id=".$this->venda->id."'>Acessar minha reserva</a>";
          $tplemail = new Template("../templates/tpl_email_ecommerce.html");
          $tplemail->CONTEUDO = $html;
          $this->mail_html($this->participante->email,$this->REMETENTE, 'Vendas Obra de Maria DF', $tplemail->showString());
            return 'pagamento gerado com suscesso';
        break;
        case 'canceled':
            
        break;
        case 'contested':
        break;
        case 'settled':
        break;
    }
    


    
    
    return "n�o foi executado nenhum procedimento";
    
   
    //print_r($chargeNotification);
        } catch (GerencianetException $e) {
            throw $e;
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
                $part->getById($this->participante->id);
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
            $pag->obs = 'pagamento autom�tico vindo da gerencianet';
            $pag->abatimentoAutomatico =1;
            $pag->moeda = $om;
            $pag->participante = $this->participante;
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
		    $oG->getById($this->participante->grupo->id);
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

    

    function getMetodo(){
        return $this->payment_method == 'banking_billet' ? 'Boleto' : "Cart�o";
    }
}