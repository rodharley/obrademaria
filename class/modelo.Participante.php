<?php
class Participante extends Persistencia{
	var $id = NULL;
	var $dataInscricao;
	var $valorTotal;
	var $custoTotal;
	var $grupo = NULL;
	var $cliente = NULL;
	var $contrato;
	var $pacoteOpcional;
	var $status;
	var $voucher;
	var $idcn = null;
	var $site;

	public function STATUS_PENDENTE(){
		return 1;
	}
		public function STATUS_APROVADO(){
		return 2;
	}
		public function STATUS_DESISTENTE(){
		return 3;
	}


	public function nomeFamilia(){
	$arraynome = explode(" ",trim($this->cliente->nomeCompleto));
	$ultimo = count($arraynome);
	$strNome =  $arraynome[$ultimo-1]." / ";
	for($i = 0;$i < count($arraynome)-1 ; $i++){
	$strNome .= $arraynome[$i]." ";
	}
	return $strNome;
	}
	public function recuperaTotal($grupo,$busca = ""){
		$rs = $this->DAO_ExecutarQuery("select count(p.id) as total from ag_participante p inner join ag_cliente c on c.id = p.cliente where p.grupo = $grupo and p.status != ".$this->STATUS_DESISTENTE()." and (c.cpf like '%$busca%' or nomeCompleto like '%$busca%')");
		return $this->DAO_Result($rs,"total",0);
	}

	public function pesquisa($inicio,$fim,$grupo,$busca){
	$sql = "select p.* from ag_participante p inner join ag_cliente c on c.id = p.cliente where p.grupo = $grupo and (c.cpf like '%$busca%' or nomeCompleto like '%$busca%') order by c.nomeCompleto limit $inicio, $fim";
	return $this->getSQL($sql);
	}

	public function participantesPeriodo($datai,$dataf){
	$sql = "select * from ag_participante where dataInscricao between '".$datai."' and '".$dataf."' ORDER BY dataInscricao, grupo";
	return $this->getSQL($sql);
	}

	public function participantesGrupo($grupo,$de=0,$ate=999){
	$sql = "select p.* from ag_participante p inner join ag_cliente c on c.id = p.cliente where p.grupo = $grupo and p.status != ".$this->STATUS_DESISTENTE()." ORDER BY c.nomeCompleto limit $de, $ate";
	return $this->getSQL($sql);
	}

	public function relatListaParticipante($grupo,$status){
	$sql = "select p.* from ag_participante p inner join ag_cliente c on c.id = p.cliente where p.grupo = $grupo and p.status != $status ORDER BY c.nomeCompleto";
	return $this->getSQL($sql);
	}

	public function relatListaSaude($grupo,$status){
	$sql = "select p.* from ag_participante p inner join ag_cliente c on c.id = p.cliente where p.grupo = $grupo and p.status != $status and ((c.problemasSaude != '' and c.problemasSaude != 'N?O') or (c.restricaoAlimentar != ''  and c.restricaoAlimentar != 'N?O')) ORDER BY c.nomeCompleto";
	return $this->getSQL($sql);
	}


	public function recuperaTotalAtivo($grupo){
		$rs = $this->DAO_ExecutarQuery("select count(id) as total from ag_participante where grupo = $grupo and status != ".$this->STATUS_DESISTENTE());
		return $this->DAO_Result($rs,"total",0);
	}

	public function distribuir(){
	$oD = new Distribuicao();
	$oD->remover($this->md5_decrypt($_REQUEST['idParticipante']));
	$oQ = new Quarto();
	$oQ->id = $this->md5_decrypt($_REQUEST['idQuarto']);
	$this->getById($this->md5_decrypt($_REQUEST['idParticipante']));
	$oD->quarto = $oQ;
	$oD->participante = $this;
	$_SESSION['tupi.mensagem'] = 34;
	$oD->save();
	}
	public function removerQuarto(){
	$oD = new Distribuicao();
	$oD->remover($this->md5_decrypt($_REQUEST['idParticipante']));
	$_SESSION['tupi.mensagem'] = 35;
	}

	public function salvaContrato(){
	$this->getById($this->md5_decrypt($_REQUEST['idParticipante']));
	$this->contrato = $_REQUEST['contrato'];
	$this->save();
	$_SESSION['tupi.mensagem'] = 56;
	}

	public function incluir(){
		$oGrupo = new Grupo();
		$oGrupo->getById($oGrupo->md5_decrypt($_REQUEST['idGrupo']));
		$oCliente = new Cliente();
		if($_REQUEST['idCliente'] != ""){
			$_POST['id'] = $_REQUEST['idCliente'];
			$oCliente->alterarb();
		}else{
			$oCliente->incluir();
		}

		$this->dataInscricao = $this->convdata($_REQUEST['dataInscricao'],"ntm");
		$pacoteOpcional = isset($_REQUEST['pacoteOpcional']) ? 1 : 0;
		$this->valorTotal = $oGrupo->getValorTotal($pacoteOpcional);
		$this->custoTotal = $oGrupo->getCustoTotal($pacoteOpcional);
		$this->grupo = $oGrupo;
		$this->cliente = $oCliente;
		$this->idcn = 0;
		$this->site = 0;
		$this->contrato = "";//$this->geraContrato();
		$this->pacoteOpcional = $pacoteOpcional;
		$oSP = new StatusParticipante();
		$oSP->id = $this->STATUS_PENDENTE();
		$this->status = $oSP;
		$newid = $this->save();
		$_SESSION['tupi.mensagem'] = 24;

		//log de criacao
		$log = new LogParticipante();
		$user = new Usuario();
		$user->id = $_SESSION['ag_idUsuario'];
		$log->usuario = $user;
		$log->participante = $this;
		$log->dataHora = date("Y-m-d H:i:s");
		$log->valor = $this->valorTotal;
		$log->custo = $this->custoTotal;
		$log->save();



		//grava log de usuario
		$oLog = new LogUsuario();
		$data = date("Y-m-d H:i:s");
		$movimento = "INCLUIR PARTICIPANTE<BR> CLIENTE: ".$this->cliente->nomeCompleto."<BR> GRUPO: ".$this->grupo->nomePacote;
		$oLog->usuario = $user;
		$oLog->data = $data;
		$oLog->movimento = $movimento;
		$oLog->save();
		//fim da log

		//inclusao em quarto escolhido
		if($_REQUEST['quarto'] != "0"){
			$oD = new Distribuicao();
			$oD->remover($this->id);
			$oQ = new Quarto();
			$oQ->id = $_REQUEST['quarto'];
			$oD->quarto = $oQ;
			$oD->participante = $this;
			$oD->save();
		}else{
			if($_REQUEST['nomeQuarto'] != ""){
			$oD = new Distribuicao();
			$oD->remover($this->id);
			$oQ = new Quarto();
			$oQ->numero = $_REQUEST['nomeQuarto'];
			$oQ->capacidade = $_REQUEST['pessoasQuarto'] != "" ? $_REQUEST['pessoasQuarto'] : 1 ;
			$oQ->grupo = $this->grupo;
			$oQ->save();
			$oD->quarto = $oQ;
			$oD->participante = $this;
			$oD->save();
			}
		}
		return $newid;
	}

	public function incluirPorId(){
		$arrayGrupo = explode(";",$_REQUEST['idGrupo']);
		$oGrupo = new Grupo();
		$oGrupo->getById($oGrupo->md5_decrypt($arrayGrupo[0]));
		$oCliente = new Cliente();
		$oCliente->getById($oGrupo->md5_decrypt($_REQUEST['idCliente']));
		if($this->getByIdCliente($oCliente->id,$oGrupo->id)){
		$_SESSION['tupi.mensagem'] = 52;
		}else{
		$this->dataInscricao = date("Y-m-d");
		$pacoteOpcional = $arrayGrupo[1] == 1 ? 1 : 0;
		$this->valorTotal = $oGrupo->getValorTotal($pacoteOpcional);
		$this->custoTotal = $oGrupo->getCustoTotal($pacoteOpcional);
		$this->grupo = $oGrupo;
		$this->cliente = $oCliente;
		$this->idcn = 0;
		$this->site = 0;
		$this->contrato = "";//$this->geraContrato();
		$this->pacoteOpcional = $pacoteOpcional;
		$oSP = new StatusParticipante();
		$oSP->id = $this->STATUS_PENDENTE();
		$this->status = $oSP;
		$newid = $this->save();
		$_SESSION['tupi.mensagem'] = 24;

		//log de criacao
		$log = new LogParticipante();
		$user = new Usuario();
		$user->id = $_SESSION['ag_idUsuario'];
		$log->usuario = $user;
		$log->participante = $this;
		$log->dataHora = date("Y-m-d H:i:s");
		$log->valor = $this->valorTotal;
		$log->custo = $this->custoTotal;
		$log->save();

		//grava log de usuario
		$oLog = new LogUsuario();
		$data = date("Y-m-d H:i:s");
		$movimento = "INCLUIR PARTICIPANTE<BR> CLIENTE: ".$this->cliente->nomeCompleto."<BR> GRUPO: ".$this->grupo->nomePacote;
		$oLog->usuario = $user;
		$oLog->data = $data;
		$oLog->movimento = $movimento;
		$oLog->save();
		//fim da log
		return $newid;
		}
	}

	function salvaContratoEmNuvem(){
		$this->getById($this->md5_decrypt($_REQUEST['idParticipante']));
		$oGrupo = $this->grupo;
		$oCliente = $this->cliente;
		//contratos em nuvem
		if($this->pacoteOpcional == 1){
			$taxaAdesao = $this->money($oGrupo->valorAdesao + $oGrupo->valorAdesaoOpcional,"atb");
		}else{
			$taxaAdesao = $this->money($oGrupo->valorAdesao,"atb");
		}
		switch ($oGrupo->modeloContrato) {
			case 'contrato1.php':
				# code...
				$layout = "1";
				break;
				case 'contrato2.php':
				# code...
				$layout = "6";
				break;
				case 'contrato3.php':
				# code...
				$layout = "7";
				break;
				case 'contrato4.php':
				# code...
				$layout = "8";
				break;
			default:
				# code...
				$layout = "1";
				break;
		}

		$data = array("identificadorLayout"=>$layout,
			"numeroControleEmpresa"=>$this->id,
			"documentoCliente"=>$oCliente->cpf,
			"nomeCliente"=>utf8_encode($oCliente->nomeCompleto),
			"emailCliente"=>$oCliente->email,
			"variaveis"=> array(
				array("nome"=>"nomeCompleto","valor"=>utf8_encode($oCliente->nomeCompleto)),
				array("nome"=>"estado_civil","valor"=>utf8_encode($oCliente->estadoCivil->descricao)),
				array("nome"=>"rg","valor"=>$oCliente->rg),
				array("nome"=>"rgOrgaoExpedidor","valor"=>$oCliente->orgaoEmissorRg),
				array("nome"=>"cpf","valor"=>$oCliente->cpf),
				array("nome"=>"endereco","valor"=>utf8_encode($oCliente->endereco)),
				array("nome"=>"cidade","valor"=>utf8_encode($oCliente->cidadeEndereco)),
				array("nome"=>"uf","valor"=>$oCliente->estadoNascimento),
				array("nome"=>"nacionalidade","valor"=>utf8_encode($oCliente->nacionalidade)),				
				array("nome"=>"taxaAdesao","valor"=>$taxaAdesao),
				array("nome"=>"CIFRAO","valor"=>$oGrupo->moeda->cifrao),
				array("nome"=>"total","valor"=>$this->money($this->valorTotal,"atb")),
				array("nome"=>"totalPassagem","valor"=>$_REQUEST['valorPassagem']),
				array("nome"=>"nometestemunha1","valor"=>utf8_encode($_REQUEST['nometestemunha1'])),
				array("nome"=>"nometestemunha2","valor"=>utf8_encode($_REQUEST['nometestemunha2'])),
				array("nome"=>"rgtestemunha1","valor"=>$_REQUEST['rgtestemunha1']),
				array("nome"=>"rgtestemunha2","valor"=>$_REQUEST['rgtestemunha2']),
				array("nome"=>"dia","valor"=>date("d")),
				array("nome"=>"mes","valor"=>utf8_encode($this->mesExtenso(date("m")))),
				array("nome"=>"ano","valor"=>date("Y"))));				
			
				
		$ret = $this->loginContratosEmnuvem();
		$headers = array('Accept' => 'application/json','X-Token'=>$ret->jwt,'Content-Type'=>'application/json; charset=utf-8');
		$query = Unirest\Request\Body::json($data);
	
		$response = Unirest\Request::post($this->endpointcn.'documentos/criar-documento', $headers, $query);
		 $oLog = new LogUsuario();
		 $user = new Usuario();
		$user->id = $_SESSION['ag_idUsuario'];
		$data = date("Y-m-d H:i:s");
		$movimento = "LOG NUMERO ".$this->id." CONTRATO EM NUVEM: ".$response->code."-".$response->raw_body;
		$oLog->usuario = $user;
		$oLog->data = $data;
		$oLog->movimento = $movimento;
		$oLog->save();
		 
		 if($response->code == 200){		
			$this->idcn =$response->body->identificadorDocumento;
			$this->save();
			$_SESSION['tupi.mensagem'] = 65;
		 }else{
			$_SESSION['tupi.mensagem'] = 66;
		 }
		 
		 return $response->body->message;
		 
	}

	function reativar(){
	$this->getById($this->md5_decrypt($_REQUEST['idParticipante']));
	$oS = new StatusParticipante();
		$oGrupo = new Grupo();
		$oGrupo->getById($this->grupo->id);
		$this->valorTotal = $oGrupo->getValorTotal($this->pacoteOpcional);
		$this->custoTotal = $oGrupo->getCustoTotal($this->pacoteOpcional);
		$oS->id = $this->STATUS_PENDENTE();
		$this->status = $oS;
		$this->dataInscricao = date("Y-m-d");
		$this->save();

		//log de criacao
		$log = new LogParticipante();
		$user = new Usuario();
		$user->id = $_SESSION['ag_idUsuario'];
		$log->usuario = $user;
		$log->participante = $this;
		$log->dataHora = date("Y-m-d H:i:s");
		$log->valor = $this->valorTotal;
		$log->custo = $this->custoTotal;
		$log->save();

		//grava log de usuario
		$oLog = new LogUsuario();
		$data = date("Y-m-d H:i:s");
		$movimento = "REATIVAR PARTICIPANTE<BR> CLIENTE: ".$this->cliente->nomeCompleto."<BR> GRUPO: ".$this->grupo->nomePacote;
		$oLog->usuario = $user;
		$oLog->data = $data;
		$oLog->movimento = $movimento;
		$oLog->save();
		//fim da log
	}


	function editar(){
		try{
		$this->getById($_REQUEST['id']);
		if($_REQUEST['opcional'] == 0){
			$this->valorTotal = $this->money($_REQUEST['valorTotal'],"bta");
		}else{

			if($this->pacoteOpcional == 0){
			$oGrupo = new Grupo();
			$oGrupo->getById($this->grupo->id);
			$this->valorTotal = $oGrupo->getValorTotal($_REQUEST['opcional']);
			}else{
			$this->valorTotal = $this->money($_REQUEST['valorTotal'],"bta");
			}

		}
		$this->custoTotal = $this->money($_REQUEST['custoTotal'],"bta");
		$this->dataInscricao = $this->convdata($_REQUEST['dataInscricao'],"ntm");
		$this->pacoteOpcional = $_REQUEST['opcional'];
		$this->save();
		$this->atualiza_status();



		//log de criacao
		$log = new LogParticipante();
		$user = new Usuario();
		$user->id = $_SESSION['ag_idUsuario'];
		$log->usuario = $user;
		$log->participante = $this;
		$log->dataHora = date("Y-m-d H:i:s");
		$log->valor = $this->valorTotal;
		$log->custo = $this->custoTotal;
		$log->save();

		//grava log de usuario
		$oLog = new LogUsuario();
		$data = date("Y-m-d H:i:s");
		$movimento = "ALTERAR PARTICIPANTE<BR> CLIENTE: ".$this->cliente->nomeCompleto."<BR> GRUPO: ".$this->grupo->nomePacote;
		$oLog->usuario = $user;
		$oLog->data = $data;
		$oLog->movimento = $movimento;
		$oLog->save();
		//fim da log

		$_SESSION['tupi.mensagem'] = 48;
		}catch (Exception $e){
		$_SESSION['tupi.mensagem'] = 49;
		}
	}


		Function editarSeguro(){
		try{
		$this->getById($_REQUEST['id']);
		$this->voucher = $_REQUEST['voucher'];
		$this->save();
		$user = new Usuario();
		$user->id = $_SESSION['ag_idUsuario'];
		//grava os tickets
		$oVoo = new Voo();
		$rsVoo = $oVoo->getRows(0,999,array("id"=>"asc"),array("grupo"=>" = ".$this->grupo->id));
		foreach($rsVoo as $key => $voo){
		$oTicket = new Ticket();
		$oTicket->getRow(array("voo"=>" = ".$voo->id,"participante"=>" = ".$this->id));
		$oTicket->voo = $voo;
		$oTicket->participante = $this;
		$oTicket->ticket = $_REQUEST['ticket'.$voo->id];
		$oTicket->reserva = $_REQUEST['reserva'.$voo->id];
		$oTicket->save();
		}


		//grava log de usuario
		$oLog = new LogUsuario();
		$data = date("Y-m-d H:i:s");
		$movimento = "ALTERAR SEGURO DO PARTICIPANTE<BR> CLIENTE: ".$this->cliente->nomeCompleto."<BR> GRUPO: ".$this->grupo->nomePacote;
		$oLog->usuario = $user;
		$oLog->data = $data;
		$oLog->movimento = $movimento;
		$oLog->save();
		//fim da log

		$_SESSION['tupi.mensagem'] = 48;
		}catch (Exception $e){
		$_SESSION['tupi.mensagem'] = 49;
		}
	}


	function excluir(){
		$this->getById($this->md5_Decrypt($_REQUEST['idParticipante']));
		$this->apagaLogs();
		$this->apagaPagamentos();
		$this->apagaAbatimentos();
		$this->apagaQuartos();
		$this->delete($this->id);

		//grava log de usuario
		$oLog = new LogUsuario();
		$user = new Usuario();
		$user->id = $_SESSION['ag_idUsuario'];
		$data = date("Y-m-d H:i:s");
		$movimento = "EXCLUIR PARTICIPANTE<BR> CLIENTE: ".$this->cliente->nomeCompleto."<BR> GRUPO: ".$this->grupo->nomePacote;
		$oLog->usuario = $user;
		$oLog->data = $data;
		$oLog->movimento = $movimento;
		$oLog->save();
		//fim da log

		$_SESSION['tupi.mensagem'] = 55;
	}

	function apagaLogs(){
	$log = new LogParticipante();
	$log->apagaLogsParticpante($this->id);
	}

	function apagaPagamentos(){
	$pag = new Pagamento();
	$pag->apagaPagamentosParticpante($this->id);
	}

	function apagaAbatimentos(){
		$sql = "delete from ag_abatimento where idParticipante = ".$this->id;
		$this->DAO_ExecutarQuery($sql);
	}

	function apagaQuartos(){
	$d = new Distribuicao();
	$d->remover($this->id);
	}

	function cancelar(){
		$this->getById($this->md5_Decrypt($_REQUEST['idParticipante']));
		$oPag = new Pagamento();
		$fp = new FinalidadePagamento();
		$rs = $oPag->getRows(0,999,array(),array("participante"=>"=".$this->id,"cancelado"=>"=0","finalidade"=>"!=".$fp->CANCELAMENTO()));
		foreach($rs as $key => $pag){
			$pag->cancelarPagamento();
		}

		if($_REQUEST['valorMulta'] != "" && $_REQUEST['valorMulta'] > 0){
		$oPag->participante = $this;
		$oPag->dataPagamento = date("Y-m-d");
    	$oPag->valorPagamento = $this->money($_REQUEST['valorMulta'],"bta");
		$oPag->obs  = "Cancelamento de Inscri??o - Multa Recis?ria";
		$oPag->abatimentoAutomatico = 0;
		$oPag->cotacaoReal = $this->money($_REQUEST['cotacaoReal'],"bta");
		$oPag->cotacaoMoedaReal = 0;
		$oPag->parcela = 1;
		$oPag->cancelado = 0;
		$oPag->devolucao = 0;
		$oFinalidade = new FinalidadePagamento();
		$oFinalidade->id = $oFinalidade->CANCELAMENTO();
		$oPag->finalidade = $oFinalidade;
		$oMoeda = new Moeda();
		$oMoeda->id = $this->grupo->moeda->id;
		$oTipoP = new TipoPagamento();
		$oTipoP->id = $oTipoP->DINHEIRO();
		$oPag->moeda = $oMoeda;
		$oPag->tipo = $oTipoP;
		$oPag->save();
		//gera o abatimento do pagamento de cancelamento para o relat?rio
		$oG = new Grupo();
		$oG->getById($this->grupo->id);
		$oAbat = new Abatimento();
		if($oG->moeda->id == $oMoeda->DOLLAR()){
			$oAbat->valor = $oPag->CALCULA_DOLLAR();
		}else{
			$oAbat->valor = $oPag->CALCULA_REAL();
		}
		$oAbat->participante = $this;
		$oAbat->pagamento = $oPag;
		$oAbat->save();
		}

		if($_REQUEST['valorCredito'] != "" && $_REQUEST['valorCredito'] > 0){
		$oCred = new Credito();
		$oCred->cliente = $this->cliente;
		$oCred->moeda = $this->grupo->moeda;
		$oCred->valor = $this->money($_REQUEST['valorCredito'],"bta");
		$oCred->data = date("Y-m-d");
		$oCred->participante = $this;
		$oCred->obs = "Cancelamento de Inscri??o - Cr?dito do Grupo: ".$this->grupo->nomePacote;
		$oCred->bitUtilizado = 0;
		$oCred->cotacaoReal = $this->money($_REQUEST['cotacaoReal'],"bta");
		$oCred->save();
		}

		$oS = new StatusParticipante();
		$oS->id = $this->STATUS_DESISTENTE();
		$this->status = $oS;
		$this->dataInscricao = date("Y-m-d");
		$this->save();
		$oD = new Distribuicao();
		$oD->remover($this->md5_decrypt($_REQUEST['idParticipante']));


		//grava log de usuario
		$oLog = new LogUsuario();
		$data = date("Y-m-d H:i:s");
		$usuario = new Usuario();
		$usuario->id = $_SESSION['ag_idUsuario'];
		$movimento = "CANCELAR (DESISTENTE) PARTICIPANTE<BR> CLIENTE: ".$this->cliente->nomeCompleto."<BR> GRUPO: ".$this->grupo->nomePacote;
		$oLog->usuario = $usuario;
		$oLog->data = $data;
		$oLog->movimento = $movimento;
		$oLog->save();
		//fim da log

		$_SESSION['tupi.mensagem'] = 25;


	}

	function validaExclusao(){
		return false;
	}

	function getByCpfCliente($cpf,$idGrupo){
	$sql = "select * from ag_participante p inner join ag_cliente c on c.id = p.cliente where p.grupo = $idGrupo and c.cpf = '".$cpf."'";
	$rs = $this->getSQL($sql);
	if (count($rs) > 0){
	$this->getById($rs[0]->id);
	return true;
	}else{
	return false;
	}
	}
	function getByCpfNomeCliente($cpf,$nome,$idGrupo){
	$sql = "select * from ag_participante p inner join ag_cliente c on c.id = p.cliente where p.grupo = $idGrupo and (c.cpf = '".$cpf."' or c.nomeCompleto like '".$nome."')";
	$rs = $this->getSQL($sql);
	if (count($rs) > 0){
	$this->getById($rs[0]->id);
	return true;
	}else{
	return false;
	}
	}

	function getByIdCliente($id,$idGrupo){
	$sql = "select * from ag_participante p inner join ag_cliente c on c.id = p.cliente where p.grupo = $idGrupo and c.id = ".$id;
	$rs = $this->getSQL($sql);
	if (count($rs) > 0){
	$this->getById($rs[0]->id);
	return true;
	}else{
	return false;
	}
	}

	function atualiza_status(){
		$total  = $this->valorTotal;
		$sql = "select sum(valor) as total from ag_abatimento a inner join ag_pagamento p on p.id = a.idPagamento where (p.pago = 1 or p.site = 0) and a.idParticipante = ".$this->id;
		$rs = $this->DAO_ExecutarQuery($sql);
		$totalAbatimento = $this->money($this->money($this->DAO_Result($rs,"total",0),"atb"),"bta");
		if($totalAbatimento >= $total || $total == 0)
		$this->status->id = $this->STATUS_APROVADO();
		else
		$this->status->id = $this->STATUS_PENDENTE();
		$this->save();
	}

	function recuperaValorPago(){
	//$sql = "select sum(valor) as total from ag_abatimento where idPagamento in (select id from ag_pagamento where participante = ".$this->id.")";
	$sql = "select sum(valor) as total from ag_abatimento where idParticipante = ".$this->id;
		$rs = $this->DAO_ExecutarQuery($sql);
		$totalAbatimento = $this->DAO_Result($rs,"total",0);
		return $totalAbatimento;
	}
	function recuperaTotaisCamisetas($idGrupo){
	$sql = "SELECT COUNT( c.id ) AS total, c.tamanhoCamisa FROM ag_cliente c INNER JOIN ag_participante p ON p.cliente = c.id WHERE p.grupo =$idGrupo GROUP BY c.tamanhoCamisa" ;
		$rs = $this->DAO_ExecutarQuery($sql);
		return $rs;
	}


	function recuperaValorTodosPagamentos($idMoeda){
		$oPagamento = new Pagamento();
		$om = new Moeda();
		$total = 0;
		$rs = $oPagamento->getRows(0,999,array(),array("participante"=>"=".$this->id,"cancelado"=>"=0"));
		foreach($rs as $key => $pag){
		if($idMoeda == $om->DOLLAR()){
			$total += $pag->CALCULA_DOLLAR();
		}else{
			$total += $pag->CALCULA_REAL();
		}
		}

		return $total;
	}

	function recuperaValorTodosAbatimentos($idMoeda){
		$oPagamento = new Pagamento();
		$oAbatimento = new Abatimento();
		$om = new Moeda();
		$total = 0;
		$rs = $oAbatimento->getRows(0,999,array(),array("participante"=>"=".$this->id));
		foreach($rs as $key => $abat){
		if($idMoeda == $om->DOLLAR()){
			$total += $abat->getValorDollar();
		}else{
			$total += $abat->getValorReal();
		}
		}

		return $total;
	}

	public function saveBySite($obGrupo,$obCliente,$opcional){
		$this->dataInscricao = date("Y-m-d");
		$this->valorTotal = $obGrupo->getValorTotal($opcional);
		$this->custoTotal = $obGrupo->getCustoTotal($opcional);
		$this->grupo = $obGrupo;
		$this->cliente = $obCliente;
		$this->idcn = 0;
		$this->site = 1;
		$this->contrato = "";//$this->geraContrato();
		$this->pacoteOpcional = $opcional;
		$oSP = new StatusParticipante();
		$oSP->id = $this->STATUS_PENDENTE();
		$this->status = $oSP;
		$newid = $this->save();
		return $newid;
	}
}
?>
