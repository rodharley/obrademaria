<?php 
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;


class VendaSite extends Persistencia {
    var $id = NULL;
	var $participante = NULL;
    var $total;
    var $create_at;
    var $acompanhante1;
    var $acompanhante2;
    var $acompanhante3;
    var $acompanhante4;
    var $cotacao;
    var $opcional;
    var $formaPagamento;
    var $quantidade;
    var $tipoPagamento1;
    var $tipoPagamento2;
    var $desconto;

    public function createVenda($participante,$grupo,$opcional,$quantidade,$formaPagamento,$tipoPagamento1,$tipoPagamento2,$idAcompanhante1,$idAcompanhante2,$idAcompanhante3,$idAcompanhante4){
        $obAgenda = new Agendamento();
        $obAgenda->getById(6);
        $cotacao = 1.0;
        $desconto = 0;
        if($grupo->moeda->id != 2){    
            switch($formaPagamento){
                case 'formaAVista':  
                    $desconto = $grupo->descontoAVista;                 
                    $cotacao = $grupo->cotacaoAVista;
                break;
                case 'formaParcelado':
                    $cotacao = $grupo->cotacaoParcelado;
                break;
                case 'formaEntrada':
                    $cotacao = $grupo->cotacaoEntrada;
                break;
                default:
                    $cotacao = $obAgenda->destinatarios;
            }       
          
        }
        if($desconto > 0){
            if($opcional){
               $pacote = $grupo->valorPacote+$grupo->valorPacoteOpcional;
            }else{
                $pacote = $grupo->valorPacote;
            }
        $valorDescontado = ($pacote)*($desconto/100);
        }else{
        $valorDescontado = 0;
        }

        $valor = $grupo->getValorTotal($opcional) * $quantidade;
        $total = ($valor) - ($valorDescontado*$quantidade);        
        $totalReal = $total*$cotacao;
        $this->participante = $participante;
        $this->total = $this->money($totalReal,"bta");           
        $this->created_at = date("Y-m-d H:i:s");
        $this->acompanhante1 = $idAcompanhante1!=0 ? $idAcompanhante1 :null;
        $this->acompanhante2 = $idAcompanhante2!=0 ? $idAcompanhante2 :null;
        $this->acompanhante3 = $idAcompanhante3!=0 ? $idAcompanhante3 :null;
        $this->acompanhante4 = $idAcompanhante4!=0 ? $idAcompanhante4 :null;
        $this->cotacao = $cotacao;
        $this->formaPagamento = $formaPagamento;
        $this->opcional = $opcional;
        $this->quantidade = $quantidade;
        $this->tipoPagamento1 = $tipoPagamento1;
        $this->tipoPagamento2 = $tipoPagamento2;
        $this->desconto = $desconto;
        return $this->save();
    }
    



    function incluirPagamentoSiteTransferencia($valor,$obs){
        $qtd = 1;
        if($this->acompanhante1 != null)
            $qtd++;
        if($this->acompanhante2 != null)
            $qtd++;
        if($this->acompanhante3 != null)
            $qtd++;
        if($this->acompanhante4 != null)
            $qtd++;
            
        for($i=1;$i<=$qtd;$i++){
            $part = new Participante();
            $oTipoP = new TipoPagamento();
            $oFin = new FinalidadePagamento();  
            $pag = new Pagamento();          
            $om = new Moeda();
            $ott = new TipoTransferencia();
		    $ott->id = $ott->TRANSFERENCIA();
            $oTipoP->id = $oTipoP->BANCO();
            $oFin->id = 1;
            $om->id = $om->REAL();
             

            if($i==1){
                $part->getById($this->participante->id);
            }else if($i == 2){
                $part->getById($this->acompanhante1);
            }else if($i == 3){
                $part->getById($this->acompanhante2);
            }else if($i == 4){
                $part->getById($this->acompanhante3);
            }else if($i == 5){
                $part->getById($this->acompanhante4);
            }
            
            $pag->dataPagamento = date("Y-m-d");
            $pag->valorPagamento = $this->money($valor/$qtd,"bta");
            $pag->obs = $obs;
            $pag->abatimentoAutomatico =1;
            $pag->moeda = $om;
            $pag->participante = $this->participante;
            $pag->tipo = $oTipoP;
            $pag->finalidade = $oFin;
            $pag->cancelado = 0;
            $pag->devolucao = 0;
            $pag->valorParcela = 0;
            $pag->cotacaoMoedaReal=0;
            $pag->cotacaoReal = $this->cotacao;
            $pag->parcela = 1;
            $pag->site = 1;
            $pag->pago = 0;
            $pag->tipoTransferencia = $ott;
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
    
    function incluirPagamentoSiteCheque($valor,$dataPagamento,$obs){

        $qtd = 1;
        if($this->acompanhante1 != null)
            $qtd++;
        if($this->acompanhante2 != null)
            $qtd++;
        if($this->acompanhante3 != null)
            $qtd++;
        if($this->acompanhante4 != null)
            $qtd++;
            
        for($i=1;$i<=$qtd;$i++){
            $part = new Participante();
            $oTipoP = new TipoPagamento();
            $oFin = new FinalidadePagamento();  
            $pag = new Pagamento();          
            $om = new Moeda();
            $ob = new Banco();
            $Status = new StatusCheque();
            $ob->id = 1;	
            $Status->id = 1;

           

		    
            $oTipoP->id = $oTipoP->CHEQUE();
            $oFin->id = 1;
            $om->id = $om->REAL();
            if($i==1){
                $part->getById($this->participante->id);
            }else if($i == 2){
                $part->getById($this->acompanhante1);
            }else if($i == 3){
                $part->getById($this->acompanhante2);
            }else if($i == 4){
                $part->getById($this->acompanhante3);
            }else if($i == 5){
                $part->getById($this->acompanhante4);
            }
            
            $pag->dataPagamento = $dataPagamento;
            $pag->valorPagamento = $this->money($valor/$qtd,"bta");
            $pag->obs = $obs;
            $pag->abatimentoAutomatico =1;
            $pag->moeda = $om;
            $pag->participante = $this->participante;
            $pag->tipo = $oTipoP;
            $pag->finalidade = $oFin;
            $pag->cancelado = 0;
            $pag->devolucao = 0;
            $pag->valorParcela = 0;
            $pag->cotacaoMoedaReal=0;
            $pag->cotacaoReal = $this->cotacao;
            $pag->parcela = 1;
            $pag->site = 1;
            $pag->pago = 0;


            
		    //cheque
            $pag->banco = $ob;
        	$pag->emissorCheque =  $this->participante->cliente;
		    $pag->numeroCheque =  "";
		    $pag->dataCompensacao  = '';           
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

public function printReserva(){
    return str_pad($this->id,10,"0",STR_PAD_LEFT);
}


function printFormaPagamento(){
    switch ($this->formaPagamento) {
        case 'formaAVista':
            return "À Vista";
            break;
            case 'formaEntrada':
                return "Entrada + Parcelas";
                break;
                case 'formaParcelado':
                    return "Parcelado";
                    break;
        default:
            return "Não Identificado";
            break;
    }
}

public function printInfoTransferencia(){
    return '<div class="card"><div class="card-body">
        Contas para transferência:<br/>
        Banco do Brasil<br/>
        <img src="img/bancos/bb.png" width="48"/>
        Ag. 0452-9 - Cc. 138706-5
        <br/><br/>
        Itaú<br/>
        <img src="img/bancos/itau.png" width="48"/>
        Ag. 522 - Cc 6071-4
    </div></div>';
}
public function printInfoCheque(){
    return '<div class="card"><div class="card-body">
    Pagamentos em cheque, deve entrar em contato <br/>
    Obra de Maria DF 
    Telefone:<br/> +55 61 3201-5116
    WhatsApp:<br/>
    <img src="img/whatsapp.png" width="48"/>
    +55 61 98100-7508
    </div></div>';
}



public function pesquisa($inicio,$fim,$idGrupo){
$sql = "select v.* from ag_venda_site v inner join ag_participante p on p.id = v.id_participante where p.grupo =".$idGrupo." limit $inicio, $fim";
return $this->getSQL($sql);
}

public function recuperaTotal($idGrupo){
    $rs = $this->DAO_ExecutarQuery("select count(v.id) as total from ag_venda_site v inner join ag_participante p on p.id = v.id_participante where p.grupo =".$idGrupo);
		return $this->DAO_Result($rs,"total",0);

}
}
