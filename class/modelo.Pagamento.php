<?php
class Pagamento extends Persistencia{
	var $id = NULL;
	var $dataPagamento;
	var $valorPagamento;
	var $obs;
	var $abatimentoAutomatico;
	var $cotacaoMoedaReal;
	var $cotacaoReal;
	var $codAutorizacao;
	var $numeroCheque;
	var $emissorCheque = NULL;
	var $dataCompensacao;
	var $parcela;
	var $participante = NULL;
	var $moeda = NULL;
	var $tipo = NULL;
	var $banco = NULL;
	var $tipoTransferencia = NULL;
	var $creditoCliente = NULL;
	var $finalidade = NULL;
	var $cancelado;
	var $devolucao;
	var $bandeira = NULL;
	var $valorParcela;
	var $numeroCartao;
	var $site;
	var $pago;
	
	public function getPagamentos($grupo,$tipo){
	$sql = "select p.* from ag_pagamento p inner join ag_participante c on c.id = p.participante where c.grupo = $grupo and p.tipo = $tipo and p.bitcancelado = 0 order by dataPagamento desc";	
	return $this->getSQL($sql);
	}
	public function getPagamentosParticipanteNaoPagos($idparticipante){
		$sql = "select p.* from ag_pagamento p where p.participante = $idparticipante and p.site = 1 and pago = 0";	
		return $this->getSQL($sql);
		}
	

	

	public function getValorPagamentosPorTipoeParticipante($participante,$tipo,$obs){
		$sql = "select sum(valorPagamento) as total from ag_pagamento p where p.participante = $participante and p.tipo = $tipo and p.bitcancelado = 0 and obs like '%$obs'";	
		return $this->DAO_ExecutarQuery($sql);
		}
	
	public function pagamentosPeriodo($datai,$dataf){
	$sql = "select * from ag_pagamento where id in (select min(id) from ag_pagamento where dataPagamento between '".$datai."' and '".$dataf."' group by participante) order by dataPagamento";
	return $this->getSQL($sql);
	}
	
	public function primeiroPagamentoCliente($idParticipante){
	$sql = "select * from ag_pagamento where id = (select a.idPagamento from ag_abatimento a inner join ag_pagamento p on p.id = a.idPagamento where a.idParticipante = $idParticipante order by p.dataPagamento limit 1) order by dataPagamento";
	return $this->getSQL($sql);
	}
	
	function cancelarPagamento(){
		$this->apagaAbatimentos();
		$this->apagaCheques();
		$this->apagaCarnes();
		$oTipoP = new TipoPagamento();
		//verificar se o pagamento era do tipo credito de cliente, para estornar o credito de volta ao cliente
		if($this->tipo->id == $oTipoP->CREDITO()){
			$oCredito = new Credito();
			$oCredito->getById($this->creditoCliente->id);
			$oCredito->bitUtilizado = 1;
			$oCredito->save();
		}
		
		$this->cancelado = 1;
		$this->save();
		//grava log de pagamento
		$oLog = new LogUsuario();
		$usuario = new Usuario();
		$usuario->id = $_SESSION['ag_idUsuario'];
		$data = date("Y-m-d H:i:s");
		$movimento = "CANCELAR PAGAMENTO<BR> MOEDA: ".$this->moeda->descricao."<BR> VALOR: ".$this->money($this->valorPagamento,"atb")."<BR> TIPO: ".$this->tipo->descricao."<BR> CLIENTE: ".$this->participante->cliente->nomeCompleto."<BR> GRUPO: ".$this->participante->grupo->nomePacote;
		$oLog->usuario = $usuario;
		$oLog->data = $data;
		$oLog->movimento = $movimento;
		$oLog->save();		
		//fim da log
		$_SESSION['tupi.mensagem'] = 37;
	}
	
	function excluirPagamento(){
		$this->apagaAbatimentos();
		$this->apagaCheques();
		$this->apagaCarnes();
		$oTipoP = new TipoPagamento();
		//verificar se o pagamento era do tipo credito de cliente, para estornar o credito de volta ao cliente
		if($this->tipo->id == $oTipoP->CREDITO()){
			$oCredito = new Credito();
			$oCredito->getById($this->creditoCliente->id);
			$oCredito->bitUtilizado = 1;
			$oCredito->save();
		}
		
		$this->delete($this->id);
		
		//grava log de pagamento
		$oLog = new LogUsuario();
		$usuario = new Usuario();
		$usuario->id = $_SESSION['ag_idUsuario'];
		$data = date("Y-m-d H:i:s");
		$movimento = "EXCLUIR PAGAMENTO<BR> MOEDA: ".$this->moeda->descricao."<BR> VALOR: ".$this->money($this->valorPagamento,"atb")."<BR> TIPO: ".$this->tipo->descricao."<BR> CLIENTE: ".$this->participante->cliente->nomeCompleto."<BR> GRUPO: ".$this->participante->grupo->nomePacote;
		$oLog->usuario = $usuario;
		$oLog->data = $data;
		$oLog->movimento = $movimento;
		$oLog->save();		
		//fim da log
		$_SESSION['tupi.mensagem'] = 37;
	}
	
	function apagaPagamentosParticpante($idParticipante){
		$sql = "select id from ag_pagamento where participante = ".$idParticipante;
		$rs = $this->DAO_ExecutarQuery($sql);
		while ($row = $this->DAO_GerarArray($rs)){
			$this->getById($row['id']);
			$this->apagaAbatimentos();
			$this->apagaCheques();
			$this->apagaCarnes();
			$oTipoP = new TipoPagamento();
			//verificar se o pagamento era do tipo credito de cliente, para estornar o credito de volta ao cliente
			if($this->tipo->id == $oTipoP->CREDITO()){
				$oCredito = new Credito();
				$oCredito->getById($this->creditoCliente->id);
				$oCredito->bitUtilizado = 1;
				$oCredito->save();
			}
			$this->delete($this->id);
			//grava log de pagamento
			$oLog = new LogUsuario();
			$usuario = new Usuario();
			$usuario->id = $_SESSION['ag_idUsuario'];
			$data = date("Y-m-d H:i:s");
			$movimento = "EXCLUIR PAGAMENTO<BR> MOEDA: ".$this->moeda->descricao."<BR> VALOR: ".$this->money($this->valorPagamento,"atb")."<BR> TIPO: ".$this->tipo->descricao."<BR> CLIENTE: ".$this->participante->cliente->nomeCompleto."<BR> GRUPO: ".$this->participante->grupo->nomePacote;
			$oLog->usuario = $usuario;
			$oLog->data = $data;
			$oLog->movimento = $movimento;
			$oLog->save();		
			//fim da log
		}
		return true;
	}
	
	
	function apagaCheques(){
		$sql = "delete from ag_cheque where idPagamento = ".$this->id;
		$this->DAO_ExecutarQuery($sql);	
		
	}
	
	function apagaCarnes(){
		$sql = "delete from ag_carne where idPagamento = ".$this->id;
		$this->DAO_ExecutarQuery($sql);	
		
	}
	
	function apagaAbatimentos(){
		$oabat = new Abatimento();
		$sql = "select * from ag_abatimento where idPagamento = ".$this->id;
		$rsabats = $oabat->getSQL($sql);	
		$opartic = new Participante();
		foreach($rsabats as $key => $abat){
			$opartic->getById($abat->participante->id);	
			$abat->delete($abat->id);
			$opartic->atualiza_status();
		}		
	}
	
	function alterarPagamento(){
	$this->getById($_REQUEST['id']);
	$this->apagaAbatimentos();
	$this->apagaCheques();
	$this->apagaCarnes();	
	$oPartic = new Participante();
	$oPartic->getById($this->md5_decrypt($_REQUEST['idParticipante']));
	$oTipoP = new TipoPagamento();
	$oFin = new FinalidadePagamento();
	$oFin->id = $_REQUEST['finalidade'];
	$oTipoP->getById($_REQUEST['tipo']);
	$om = new Moeda();
	$om->getById($_REQUEST['moeda']);
	
	//verificar se o pagamento era do tipo credito de cliente, para estornar o credito de volta ao cliente
	if($this->tipo->id == $oTipoP->CREDITO()){
		$oCredito = new Credito();
		$oCredito->getById($this->creditoCliente->id);
		$oCredito->bitUtilizado = 0;
		$oCredito->save();
	}
	
	$this->finalidade = $oFin;
	$this->dataPagamento = $this->convdata($_REQUEST['dataPagamento'],"ntm");
	$this->valorPagamento = $this->money($_REQUEST['valorPagamento'],"bta");
	$this->obs = $_REQUEST['obs'];
	$this->abatimentoAutomatico = isset($_REQUEST['abatimentoAutomatico']) ? 1 : 0;
	$this->moeda = $om;
	$this->participante = $oPartic;
	$this->tipo = $oTipoP;
	$this->devolucao = $_REQUEST['dev'];
    $this->valorParcela = 0;
	switch ($_REQUEST['tipo']){
	case $oTipoP->DINHEIRO() :
		$this->valorPagamento = $this->money($_REQUEST['valorPagamento'],"bta");
		$this->cotacaoMoedaReal  = isset($_REQUEST['cotacaoMoedaReal']) ? $this->money($_REQUEST['cotacaoMoedaReal'],"bta") : 0;
		$this->cotacaoReal  = $this->money($_REQUEST['cotacaoReal'],"bta");
	break;
	case $oTipoP->CARTAO() :
		$this->cotacaoMoedaReal  = 0;
		$this->valorPagamento = $this->money($_REQUEST['valorPagamento'],"bta");
		$this->valorParcela = $this->money($_REQUEST['valorParcela'],"bta");
		$this->numeroCartao = $_REQUEST['numeroCartao'];
		$this->cotacaoReal  = $this->money($_REQUEST['cotacaoReal'],"bta");
		$this->codAutorizacao = isset($_REQUEST['codAutorizacao']) ? $_REQUEST['codAutorizacao'] : "";
		$this->parcela = $_REQUEST['parcelaCartao'];
		$oband = new BandeiraCartao();
		$oband->id = $_REQUEST['bandeira'];
		$this->bandeira = $oband;
	break;
	case $oTipoP->DEBITO() :
		$this->cotacaoMoedaReal  = 0;
		$this->valorParcela = 0;
		$this->valorPagamento = $this->money($_REQUEST['valorPagamento'],"bta");
		$this->cotacaoReal  = $this->money($_REQUEST['cotacaoReal'],"bta");
        $this->codAutorizacao = isset($_REQUEST['codAutorizacao']) ? $_REQUEST['codAutorizacao'] : "";
		$this->numeroCartao = $_REQUEST['numeroCartao'];
		$oband = new BandeiraCartao();
		$oband->id = $_REQUEST['bandeira'];
		$this->bandeira = $oband;
	break;
	case $oTipoP->CHEQUE() :
		$ob = new Banco();
		$ob->id = $_REQUEST['banco'];		
		$this->banco = $ob;
		$Status = new StatusCheque();
		$Status->id = 1;
		$oclienteEmissor = new Cliente();
		$arrayEmissor = explode("-",$_REQUEST['nomeEmissor']);
		if(count($arrayEmissor) > 1){
		$oclienteEmissor->id = $arrayEmissor[1];
		}else{
		$oec = new EstadoCivil();
		$oec->id = 1;
		$oclienteEmissor->nomeCompleto = $_REQUEST['nomeEmissor'];
		$oclienteEmissor->oclienteEmissor->cpf = "";
		$oclienteEmissor->estadoCivil = $oec; 
		$oclienteEmissor->dataNascimento = date("Y-m-d");
		$oclienteEmissor->sexo = "";
		$oclienteEmissor->endereco = "";
		$oclienteEmissor->bairro = "";
		$oclienteEmissor->cep = "";
		$oclienteEmissor->telefoneResidencial = "";
		$oclienteEmissor->telefoneComercial = "";
		$oclienteEmissor->celular = "";
		$oclienteEmissor->fax = "";
	    $oclienteEmissor->rg = "";
		$oclienteEmissor->orgaoEmissorRg = "";
		$oclienteEmissor->passaporte = "";
		$oclienteEmissor->nomeCracha = "";
		$oclienteEmissor->tamanhoCamisa = "";
		$oclienteEmissor->problemasSaude = "";
		$oclienteEmissor->restricaoAlimentar = "";
		$oclienteEmissor->email = "";
		$oclienteEmissor->nacionalidade = "";
		$oclienteEmissor->cidadeEndereco = "";
		$oclienteEmissor->estadoEndereco = "";
		$oclienteEmissor->paisEndereco = "";
		$oclienteEmissor->cidadeNascimento = "";
		$oclienteEmissor->paisNascimento = "";
		$oclienteEmissor->estadoNascimento = "";
		$oclienteEmissor->preferencial = 0;
		$oclienteEmissor->enviaCorrespondencia = 0; 
		$oclienteEmissor->save();	
		}		
		
		$this->parcela = 1;
		$this->emissorCheque =  $oclienteEmissor;
		$this->numeroCheque = isset($_REQUEST['numeroCheque1']) ? $_REQUEST['numeroCheque1'] : "";
		$this->dataCompensacao  = isset($_REQUEST['dataCompensacao1']) ? $this->convdata($_REQUEST['dataCompensacao1'],"ntm") : '';		
		$this->valorPagamento = $this->money($_REQUEST['valorCheque1'],"bta");
		$this->cotacaoReal  = $this->money($_REQUEST['cotacaoReal'],"bta");
		$this->cotacaoMoedaReal  = 0;	
		
	break;
	case $oTipoP->BANCO() :
		$ott = new TipoTransferencia();
		$ott->id = $_REQUEST['tipoTranferencia'];
		$this->tipoTransferencia = $ott;
		$this->valorPagamento = $this->money($_REQUEST['valorPagamento'],"bta");
		$this->cotacaoReal  = $this->money($_REQUEST['cotacaoReal'],"bta");
		$this->cotacaoMoedaReal  = 0;
	break;
	case $oTipoP->CREDITO() :
		//atualizar o credito para utilizado
		$oCredito = new Credito();
		$oCredito->getById($_REQUEST['credito']);
		$oCredito->bitUtilizado = 1;
		$oCredito->save();	
		$this->valorPagamento = $oCredito->valor;
		$this->moeda = $oCredito->moeda;
		$this->creditoCliente = $oCredito;
		$this->cotacaoReal  = $this->money($_REQUEST['cotacaoReal'],"bta");
		$this->cotacaoMoedaReal  = 0;
		$this->parcela = 1;
	break;
	case $oTipoP->CARNE() :
		$this->parcela = 1;
		$this->dataCompensacao  = isset($_REQUEST['dataVencimento1']) ? $this->convdata($_REQUEST['dataVencimento1'],"ntm") : '';
		$this->valorPagamento = $this->money($_REQUEST['valorCarne1'],"bta");	
		$this->cotacaoReal  = $this->money($_REQUEST['cotacaoReal'],"bta");
		$this->cotacaoMoedaReal  = 0;
	break;
	
	}
	$idPagamento = $this->save();
	
	//cheques
	if($oTipoP->CHEQUE() == $_REQUEST['tipo']){
		$parcelas = 0;
		$valorTotal = 0;
		for($i = 1 ;$i <= 10 ; $i++){
		 if($_REQUEST['dataCompensacao'.$i] != "" && $_REQUEST['numeroCheque'.$i] != "" && $_REQUEST['valorCheque'.$i] != ""){
		 //inclui o cheque
		 $parcelas ++;
		 $oCheque = new Cheque();
		 $oCheque->status = $Status;
 		 $oCheque->emissor = $oclienteEmissor;
		 $oCheque->numeroCheque = $_REQUEST['numeroCheque'.$i];
		 $oCheque->valor = $ob->money($_REQUEST['valorCheque'.$i],"bta");
		 $oCheque->pagamento = $this;
		 $oCheque->dataCompensacao = $ob->convdata($_REQUEST['dataCompensacao'.$i],"ntm");
		 $oCheque->parcela = $i;
		 $oCheque->save();		 
		 $valorTotal += $oCheque->valor;
		 }
		}
		$this->parcela = $parcelas;
		$this->emissorCheque =  $oclienteEmissor;
		$this->numeroCheque = isset($_REQUEST['numeroCheque1']) ? $_REQUEST['numeroCheque1'] : "";
		$this->dataCompensacao  = isset($_REQUEST['dataCompensacao1']) ? $this->convdata($_REQUEST['dataCompensacao1'],"ntm") : '';	
		$this->cotacaoReal  = $this->money($_REQUEST['cotacaoReal'],"bta");
		$this->cotacaoMoedaReal  = 0;	
		$this->valorPagamento = $this->money($valorTotal,"bta");
		$this->save();
	}
	
	//carnes
	if($oTipoP->CARNE() == $_REQUEST['tipo']){
		$parcelas = 0;
		$valorTotal = 0;
		for($i = 1 ;$i <= $_REQUEST['parcelaCarne'] ; $i++){
		 if($_REQUEST['dataVencimento'.$i] != "" && $_REQUEST['valorCarne'.$i] != ""){
		 //inclui o cheque
		 $parcelas ++;
		 $oCarne = new Carne();
		 $oCarne->valor = $oCarne->money($_REQUEST['valorCarne'.$i],"bta");
		 $oCarne->pagamento = $this;
		 $oCarne->dataVencimento = $oCarne->convdata($_REQUEST['dataVencimento'.$i],"ntm");
		 $oCarne->parcela = $i;
		 $oCarne->save();		 
		 
		 $valorTotal += $oCarne->valor;
		 }
		}
		$this->parcela = $parcelas;		
		$this->dataCompensacao  = isset($_REQUEST['dataVencimento1']) ? $this->convdata($_REQUEST['dataVencimento1'],"ntm") : '';	
		$this->cotacaoReal  = $this->money($_REQUEST['cotacaoReal'],"bta");
		$this->cotacaoMoedaReal  = 0;	
		$this->valorPagamento = $this->money($valorTotal,"bta");
		$this->save();
	}
	
	//REALIZA O ABATIMENTO AUTOMATICO DO PARTICIPANTE
	if($this->abatimentoAutomatico == 1){
		if(($oTipoP->CARTAO() == $_REQUEST['tipo'] && $this->codAutorizacao != "") || ($oTipoP->CARTAO() != $_REQUEST['tipo']) ){
		$oG = new Grupo();
		$oG->getById($this->md5_decrypt($_REQUEST['idGrupo']));
		$oAbat = new Abatimento();	
		
		if($oG->moeda->id == $om->DOLLAR()){
			$oAbat->valor = $this->devolucao == 0 ? $this->CALCULA_DOLLAR() : -$this->CALCULA_DOLLAR();
		}else{
			$oAbat->valor = $this->devolucao == 0 ? $this->CALCULA_REAL() : -$this->CALCULA_REAL();
		}	
		$oAbat->participante = $oPartic;
		$oAbat->pagamento = $this;        
		$oAbat->save();
		}
	}
	//FAZ A CONFERENCIA PARA MUDAR O STATUS DO PARTICIPANTE
	$oPartic->atualiza_status();
	
	//grava log de pagamento
		$oLog = new LogUsuario();
		$usuario = new Usuario();
		$usuario->id = $_SESSION['ag_idUsuario'];
		$data = date("Y-m-d H:i:s");
		$movimento = "ALTERAR PAGAMENTO<BR> MOEDA: ".$this->moeda->descricao."<BR> VALOR: ".$this->money($this->valorPagamento,"atb")."<BR> TIPO: ".$this->tipo->descricao."<BR> CLIENTE: ".$this->participante->cliente->nomeCompleto."<BR> GRUPO: ".$this->participante->grupo->nomePacote;
		$oLog->usuario = $usuario;
		$oLog->data = $data;
		$oLog->movimento = $movimento;
		$oLog->save();		
		//fim da log
	
	
	$_SESSION['tupi.mensagem'] = 38;
	return $idPagamento;
	}
	
	
	
function incluirPagamento(){
	$oPartic = new Participante();
	$oPartic->getById($this->md5_decrypt($_REQUEST['idParticipante']));
	$oTipoP = new TipoPagamento();
	$oFin = new FinalidadePagamento();
	$oFin->id = $_REQUEST['finalidade'];
	$oTipoP->getById($_REQUEST['tipo']);
	$om = new Moeda();
	$om->getById($_REQUEST['moeda']);
	$this->dataPagamento = $this->convdata($_REQUEST['dataPagamento'],"ntm");
	$this->obs = $_REQUEST['obs'];
	$this->abatimentoAutomatico = isset($_REQUEST['abatimentoAutomatico']) ? 1 : 0;
	$this->moeda = $om;
	$this->participante = $oPartic;
	$this->tipo = $oTipoP;
	$this->finalidade = $oFin;
	$this->cancelado = 0;
	$this->site = 0;
	$this->pago = 1;
	$this->devolucao = $_REQUEST['dev'];
	$this->valorParcela = 0;
	switch ($_REQUEST['tipo']){
	case $oTipoP->DINHEIRO() :
		$this->valorPagamento = $this->money($_REQUEST['valorPagamento'],"bta");
		$this->cotacaoMoedaReal  = isset($_REQUEST['cotacaoMoedaReal']) ? $_REQUEST['cotacaoMoedaReal'] != "" ? $this->money($_REQUEST['cotacaoMoedaReal'],"bta") : 0 : 0;
		$this->cotacaoReal  = $this->money($_REQUEST['cotacaoReal'],"bta");
		$this->parcela = 1;
	break;
	case $oTipoP->CARTAO() :
		$this->valorPagamento = $this->money($_REQUEST['valorPagamento'],"bta");
        $this->valorParcela = $this->money($_REQUEST['valorParcela'],"bta");
		$this->cotacaoMoedaReal  = 0;
		$this->cotacaoReal  = $this->money($_REQUEST['cotacaoReal'],"bta");
		$this->codAutorizacao = isset($_REQUEST['codAutorizacao']) ? $_REQUEST['codAutorizacao'] : "";
		$this->parcela = $_REQUEST['parcelaCartao'];
		$oband = new BandeiraCartao();
		$oband->id = $_REQUEST['bandeira'];
		$this->bandeira = $oband;
	break;
	case $oTipoP->DEBITO() :
		$this->cotacaoMoedaReal  = 0;
        $this->codAutorizacao = isset($_REQUEST['codAutorizacao']) ? $_REQUEST['codAutorizacao'] : "";
		$this->valorPagamento = $this->money($_REQUEST['valorPagamento'],"bta");
		$this->cotacaoReal  = $this->money($_REQUEST['cotacaoReal'],"bta");
		$this->parcela = 1;
		$oband = new BandeiraCartao();
		$oband->id = $_REQUEST['bandeira'];
		$this->bandeira = $oband;
	break;
	case $oTipoP->CHEQUE() :
		$ob = new Banco();
		$ob->id = $_REQUEST['banco'];		
		$this->banco = $ob;
		$Status = new StatusCheque();
		$Status->id = 1;
		$oclienteEmissor = new Cliente();
		$arrayEmissor = explode("-",$_REQUEST['nomeEmissor']);
		if(count($arrayEmissor) > 1){
		$oclienteEmissor->id = $arrayEmissor[1];
		}else{
		$oec = new EstadoCivil();
		$oec->id = 1;
		$oclienteEmissor->nomeCompleto = $_REQUEST['nomeEmissor'];
		$oclienteEmissor->oclienteEmissor->cpf = "";
		$oclienteEmissor->estadoCivil = $oec; 
		$oclienteEmissor->dataNascimento = date("Y-m-d");
		$oclienteEmissor->sexo = "";
		$oclienteEmissor->endereco = "";
		$oclienteEmissor->bairro = "";
		$oclienteEmissor->cep = "";
		$oclienteEmissor->telefoneResidencial = "";
		$oclienteEmissor->telefoneComercial = "";
		$oclienteEmissor->celular = "";
		$oclienteEmissor->fax = "";
	    $oclienteEmissor->rg = "";
		$oclienteEmissor->orgaoEmissorRg = "";
		$oclienteEmissor->passaporte = "";
		$oclienteEmissor->nomeCracha = "";
		$oclienteEmissor->tamanhoCamisa = "";
		$oclienteEmissor->problemasSaude = "";
		$oclienteEmissor->restricaoAlimentar = "";
		$oclienteEmissor->email = "";
		$oclienteEmissor->nacionalidade = "";
		$oclienteEmissor->cidadeEndereco = "";
		$oclienteEmissor->estadoEndereco = "";
		$oclienteEmissor->paisEndereco = "";
		$oclienteEmissor->cidadeNascimento = "";
		$oclienteEmissor->paisNascimento = "";
		$oclienteEmissor->estadoNascimento = "";
		$oclienteEmissor->preferencial = 0;
		$oclienteEmissor->enviaCorrespondencia = 0; 
		$oclienteEmissor->save();	
		}			
		
		
		
		
		
		$this->parcela = 1;
		$this->emissorCheque =  $oclienteEmissor;
		$this->numeroCheque = isset($_REQUEST['numeroCheque1']) ? $_REQUEST['numeroCheque1'] : "";
		$this->dataCompensacao  = isset($_REQUEST['dataCompensacao1']) ? $this->convdata($_REQUEST['dataCompensacao1'],"ntm") : '';
		$this->valorPagamento = $this->money($_REQUEST['valorCheque1'],"bta");	
		$this->cotacaoReal  = $this->money($_REQUEST['cotacaoReal'],"bta");
		$this->cotacaoMoedaReal  = 0;	
		
	break;
	case $oTipoP->BANCO() :
		$ott = new TipoTransferencia();
		$ott->id = $_REQUEST['tipoTranferencia'];
		$this->tipoTransferencia = $ott;
		$this->valorPagamento = $this->money($_REQUEST['valorPagamento'],"bta");
		$this->cotacaoReal  = $this->money($_REQUEST['cotacaoReal'],"bta");
		$this->cotacaoMoedaReal  = 0;
		$this->parcela = 1;
	break;
	case $oTipoP->CREDITO() :
		//atualizar o credito para utilizado
		$oCredito = new Credito();
		$oCredito->getById($_REQUEST['credito']);
		$oCredito->bitUtilizado = 1;
		$oCredito->save();
	
		$this->valorPagamento = $oCredito->valor;
		$this->moeda = $oCredito->moeda;
		$this->creditoCliente = $oCredito;
		$this->cotacaoReal  = $this->money($_REQUEST['cotacaoReal'],"bta");
		$this->cotacaoMoedaReal  = 0;
		$this->parcela = 1;
	break;
	case $oTipoP->CARNE() :
		$this->parcela = 1;
		$this->dataCompensacao  = isset($_REQUEST['dataVencimento1']) ? $this->convdata($_REQUEST['dataVencimento1'],"ntm") : '';
		$this->valorPagamento = $this->money($_REQUEST['valorCarne1'],"bta");	
		$this->cotacaoReal  = $this->money($_REQUEST['cotacaoReal'],"bta");
		$this->cotacaoMoedaReal  = 0;
	break;
	}
	$idPagamento = $this->save();
	
	
	//cheques
	if($oTipoP->CHEQUE() == $_REQUEST['tipo']){
		$parcelas = 0;
		$valorTotal = 0;
		for($i = 1 ;$i <= 10 ; $i++){
		 if($_REQUEST['dataCompensacao'.$i] != "" && $_REQUEST['numeroCheque'.$i] != "" && $_REQUEST['valorCheque'.$i] != ""){
		 //inclui o cheque
		 $parcelas ++;
		 $oCheque = new Cheque();
		 $oCheque->status = $Status;
 		 $oCheque->emissor = $oclienteEmissor;
		 $oCheque->numeroCheque = $_REQUEST['numeroCheque'.$i];
		 $oCheque->valor = $ob->money($_REQUEST['valorCheque'.$i],"bta");
		 $oCheque->pagamento = $this;
		 $oCheque->dataCompensacao = $ob->convdata($_REQUEST['dataCompensacao'.$i],"ntm");
		 $oCheque->parcela = $i;
		 $oCheque->save();		 
		 
		 $valorTotal += $oCheque->valor;
		 }
		}
		$this->parcela = $parcelas;
		$this->emissorCheque =  $oclienteEmissor;
		$this->numeroCheque = isset($_REQUEST['numeroCheque1']) ? $_REQUEST['numeroCheque1'] : "";
		$this->dataCompensacao  = isset($_REQUEST['dataCompensacao1']) ? $this->convdata($_REQUEST['dataCompensacao1'],"ntm") : '';	
		$this->cotacaoReal  = $this->money($_REQUEST['cotacaoReal'],"bta");
		$this->cotacaoMoedaReal  = 0;	
		$this->valorPagamento = $this->money($valorTotal,"bta");
		$this->save();
	}

	//carnes
	if($oTipoP->CARNE() == $_REQUEST['tipo']){
		$parcelas = 0;
		$valorTotal = 0;
		for($i = 1 ;$i <= $_REQUEST['parcelaCarne'] ; $i++){
		 if($_REQUEST['dataVencimento'.$i] != "" && $_REQUEST['valorCarne'.$i] != ""){
		 //inclui o cheque
		 $parcelas ++;
		 $oCarne = new Carne();
		 $oCarne->valor = $oCarne->money($_REQUEST['valorCarne'.$i],"bta");
		 $oCarne->pagamento = $this;
		 $oCarne->dataVencimento = $oCarne->convdata($_REQUEST['dataVencimento'.$i],"ntm");
		 $oCarne->parcela = $i;
		 $oCarne->save();		 
		 
		 $valorTotal += $oCarne->valor;
		 }
		}
		$this->parcela = $parcelas;		
		$this->dataCompensacao  = isset($_REQUEST['dataVencimento1']) ? $this->convdata($_REQUEST['dataVencimento1'],"ntm") : '';	
		$this->cotacaoReal  = $this->money($_REQUEST['cotacaoReal'],"bta");
		$this->cotacaoMoedaReal  = 0;	
		$this->valorPagamento = $this->money($valorTotal,"bta");
		$this->save();
	}
	
	//REALIZA O ABATIMENTO AUTOMATICO DO PARTICIPANTE
	if($this->abatimentoAutomatico == 1){
		if(($oTipoP->CARTAO() == $_REQUEST['tipo'] && $this->codAutorizacao != "") || ($oTipoP->CARTAO() != $_REQUEST['tipo']) ){
		$oG = new Grupo();
		$oG->getById($this->md5_decrypt($_REQUEST['idGrupo']));
		$oAbat = new Abatimento();	
		
		if($oG->moeda->id == $om->DOLLAR()){
			$oAbat->valor = $this->devolucao == 0 ? $this->CALCULA_DOLLAR() : -$this->CALCULA_DOLLAR();
		}else{
			$oAbat->valor = $this->devolucao == 0 ? $this->CALCULA_REAL() : -$this->CALCULA_REAL();
		}	
		$oAbat->participante = $oPartic;
		$oAbat->pagamento = $this;
		$oAbat->save();
		}
	}
	//FAZ A CONFERENCIA PARA MUDAR O STATUS DO PARTICIPANTE
	$oPartic->atualiza_status();
	
	//grava log de pagamento
		$oLog = new LogUsuario();
		$usuario = new Usuario();
		$usuario->id = $_SESSION['ag_idUsuario'];
		$data = date("Y-m-d H:i:s");
		$movimento = "INCLUIR PAGAMENTO<BR> MOEDA: ".$this->moeda->descricao."<BR> VALOR: ".$this->money($this->valorPagamento,"atb")."<BR> TIPO: ".$this->tipo->descricao."<BR> CLIENTE: ".$this->participante->cliente->nomeCompleto."<BR> GRUPO: ".$this->participante->grupo->nomePacote;
		$oLog->usuario = $usuario;
		$oLog->data = $data;
		$oLog->movimento = $movimento;
		$oLog->save();		
		//fim da log
	
	$_SESSION['tupi.mensagem'] = 36;	
	

return $idPagamento;
}





function CALCULA_REAL($valor = 0){
	$moeda = new Moeda();
	if($valor == 0)
		$valorCalculo = $this->valorPagamento;
	else
		$valorCalculo = $valor;
 	if($this->moeda->id == $moeda->REAL())
		return $valorCalculo;
		else if ($this->moeda->id == $moeda->DOLLAR())
			return $valorCalculo * $this->cotacaoReal;
			else	
				return $valorCalculo * $this->cotacaoMoedaReal;
}

function CALCULA_DOLLAR($valor = 0){
	$moeda = new Moeda();
	$cotReal = $this->cotacaoReal == 0 ? 1 : $this->cotacaoReal;
	if($valor == 0)
		$valorCalculo = $this->valorPagamento;
	else
		$valorCalculo = $valor;
 	if($this->moeda->id == $moeda->DOLLAR())
		return $valorCalculo;
		else if ($this->moeda->id == $moeda->REAL())
			return @$this->money(($valorCalculo/$cotReal),"bta");
			else	
				return $this->money(($valorCalculo * $this->cotacaoMoedaReal) / $cotReal,"bta");
}

function CALCULA_MOEDA($valor,$idMoeda){
	$moeda = new Moeda();
 	if($idMoeda == $moeda->DOLLAR()){
	// do dollar para moeda do pagamento	
		if ($this->moeda->id == $moeda->DOLLAR())
			return $this->arredondar_dois_decimal($valor);
		else if ($this->moeda->id == $moeda->REAL())
			return $this->money($this->arredondar_dois_decimal($valor * $this->cotacaoReal),"bta");
		else
			return $this->money($this->arredondar_dois_decimal(($valor * $this->cotacaoReal) / $this->cotacaoMoedaReal),"bta");
	}else{
	//do real para moeda do pagamento
		if ($this->moeda->id == $moeda->REAL())
			return $this->arredondar_dois_decimal($valor);
		else if ($this->moeda->id == $moeda->DOLLAR())
			return $this->money($this->arredondar_dois_decimal($valor / $this->cotacaoReal),"bta");
		else
			return $this->money($this->arredondar_dois_decimal(($valor / $this->cotacaoMoedaReal)),"bta");
	
	}		
}

}
?>