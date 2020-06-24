<?php 
use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;
class GerenciaNetCheckOut extends Persistencia {
    var $id = NULL;
	var $charge_id;
	var $status;	
	var $message = NULL;
	var $participante = NULL;
    var $total;
    var $created_at;
    var $payment_method;
    var $update_at;
    var $payment_url;
    var $token;

public function getByChargeId($chargeId){
    return $this->getRow(array("charge_id"=>"=".$chargeId));
}


function createLinkPagamento($charge_id,$mensagem,$tipoPagamento){
       
// $charge_id refere-se ao ID da transação gerada anteriormente
$params = [
  'id' => $charge_id
];

$date = new DateTime(date("Y-m-d"));
$date->add(new DateInterval('P3D'));

$body = [
  'message' => $mensagem, // mensagem para o pagador com até 80 caracteres
  'expire_at' => $date->format('Y-m-d') , // data de vencimento da tela de pagamento e do próprio boleto
  'request_delivery_address' => false, // solicitar endereço de entrega do comprador?
  'payment_method' => $tipoPagamento // formas de pagamento disponíveis
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

    function createCharge($obParticipante,$obGrupo,$quantidade,$opcional){
        
        if($obGrupo->moeda->id != 2){
        $cotacoes = file_get_contents("https://economia.awesomeapi.com.br/all/USD-BRL,EUR-BRL,BTC-BRL");
         $json = json_decode($cotacoes,true);
         $total = $obGrupo->getValorTotal($opcional);
         $totalReal = $total*$json["USD"]["ask"];
        }else{
            $totalReal = $obGrupo->getValorTotal($opcional);

        }
        if($opcional)
        $nomeItem = $obGrupo->nomePacote."+".$obGrupo->nomePacoteOpcional;
        else
        $nomeItem = $obGrupo->nomePacote;
        $item_1 = [
            'name' => $nomeItem, // nome do item, produto ou serviço
            'amount' => intval($quantidade), // quantidade
            'value' => intval(str_replace(".","",str_replace(",","",$this->money($totalReal,"atb")))) // valor (1000 = R$ 10,00) (Obs: É possível a criação de itens com valores negativos. Porém, o valor total da fatura deve ser superior ao valor mínimo para geração de transações.)
        ];
         
         
        $items =  [
            $item_1
        ];
        $metadata = [
            "custom_id"=> strval($obParticipante->id),
            "notification_url"=>$this->urlScripts."chargegn.php"
        ];
        
        // Exemplo para receber notificações da alteração do status da transação.
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

            $this->participante = $obParticipante;
            $this->charge_id = $charge['data']['charge_id'];	
            $this->status  = "new";
            $this->message = "Criação do ChargeId";
            $this->total = $this->money($totalReal*$quantidade,"bta");           
            $this->created_at = date("Y-m-d H:i:s");
            $this->save();
            return $charge;
        } catch (GerencianetException $e) {
           /* print_r($e->code);
            print_r($e->error);
            print_r($e->errorDescription);*/
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
        * Este token será recebido em sua variável que representa os parâmetros do POST
        * Ex.: $_POST['notification']
        */
        $token = $_POST["notification"];
        
        $params = [
        'token' => $token
        ];
 
    try {
        $api = new Gerencianet($this->getOptions());
        $chargeNotification = $api->getNotification($params, []);
    // Para identificar o status atual da sua transação você deverá contar o número de situações contidas no array, pois a última posição guarda sempre o último status. Veja na um modelo de respostas na seção "Exemplos de respostas" abaixo.
    
    // Veja abaixo como acessar o ID e a String referente ao último status da transação.
        
        // Conta o tamanho do array data (que armazena o resultado)
        $i = count($chargeNotification["data"]);
        // Pega o último Object chargeStatus
        $ultimoStatus = $chargeNotification["data"][$i-1];
        // Acessando o array Status
        $status = $ultimoStatus["status"];
        // Obtendo o ID da transação    
        $charge_id = $ultimoStatus["identifiers"]["charge_id"];
        // Obtendo a String do status atual
    $statusAtual = $status["current"];

    //tratar status do charge

    //atualiza o pagamento
    $this->getByChargeId($charge_id);
    $this->status =$statusAtual;
    $this->token = $token;
    $this->save();
    return true;
    
   
    //print_r($chargeNotification);
        } catch (GerencianetException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}