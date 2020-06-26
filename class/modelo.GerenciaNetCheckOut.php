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
    var $idAcompanhante1;
    var $idAcompanhante2;
    var $idAcompanhante3;
    var $idAcompanhante4;
    var $cotacao;


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
  'message' => utf8_encode($mensagem), // mensagem para o pagador com até 80 caracteres
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

    function createCharge($obParticipante,$obGrupo,$quantidade,$opcional,$idPartAcomp1,$idPartAcomp2,$idPartAcomp3,$idPartAcomp4){
        $obAgenda = new Agendamento();
        $obAgenda->getById(6);
        $cotacao = 1.0;
        if($obGrupo->moeda->id != 2){           
         $cotacao = $obAgenda->destinatarios;
        }
        $total = $obGrupo->getValorTotal($opcional);
        $totalReal = $total*$cotacao;
        
        
        if($opcional)
        $nomeItem = $obGrupo->nomePacote."+".$obGrupo->nomePacoteOpcional;
        else
        $nomeItem = $obGrupo->nomePacote;
        $item_1 = [
            'name' => utf8_encode($nomeItem), // nome do item, produto ou serviço
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
            $this->idAcompanhante1 = $idPartAcomp1!=0 ? $idPartAcomp1 :null;
            $this->idAcompanhante2 = $idPartAcomp2!=0 ? $idPartAcomp2 :null;
            $this->idAcompanhante3 = $idPartAcomp3!=0 ? $idPartAcomp3 :null;
            $this->idAcompanhante4 = $idPartAcomp4!=0 ? $idPartAcomp4 :null;
            $this->cotacao = $cotacao;
            //salva os ids dos acompanantes

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
    
    //atualiza o pagamento
    $this->getByChargeId($charge_id);
    $this->status =$statusAtual;
    $this->token = $token;
    $this->save();
    //tratar status do charge
    switch($statusAtual){
        case 'paid':
            $this->gerarPagamentos($ultimoStatus["value"]);
            return 'pagamento gerado com suscesso';
        break;
        case 'canceled':
            
        break;
        case 'contested':
        break;
        case 'settled':
        break;
    }
    


    
    
    return "não foi executado nenhum procedimento";
    
   
    //print_r($chargeNotification);
        } catch (GerencianetException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function gerarPagamentos($valorPago){
        $qtd = 1;
        if($this->idAcompanhante1 != null)
            $qtd++;
        if($this->idAcompanhante2 != null)
            $qtd++;
        if($this->idAcompanhante3 != null)
            $qtd++;
        if($this->idAcompanhante4 != null)
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
                $part->getById($this->idAcompanhante1);
            }else if($i == 3){
                $part->getById($this->idAcompanhante2);
            }else if($i == 4){
                $part->getById($this->idAcompanhante3);
            }else if($i == 5){
                $part->getById($this->idAcompanhante4);
            }
            
            $pag->dataPagamento = date("Y-m-d");
            $pag->valorPagamento = $this->money($valor,"bta");
            $pag->obs = 'pagamento automático vindo da gerencianet';
            $pag->abatimentoAutomatico =1;
            $pag->moeda = $om;
            $pag->participante = $part;
	        $pag->tipo = $oTipoP;
            $pag->finalidade = $oFin;
            $pag->cancelado = 0;
	        $pag->devolucao = 0;
            $pag->valorParcela = 0;
            $pag->cotacaoMoedaReal=0;
		    $pag->cotacaoReal = $this->cotacao;
            $pag->parcela = 1;
            $pag->save();
            $oAbat = new Abatimento();	
            $oG = new Grupo();
		    $oG->getById($this->participante->grupo->id);
            if($oG->moeda->id == $om->DOLLAR()){
                $oAbat->valor = $pag->CALCULA_DOLLAR();
            }else{
                $oAbat->valor = $pag->CALCULA_REAL();
            }	
            $oAbat->participante = $this->participante;
            $oAbat->pagamento = $pag;
            $oAbat->save();
            
	        $this->participante->atualiza_status();
        }
        
        
    }

    
}
