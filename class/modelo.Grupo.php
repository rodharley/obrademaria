<?php
class Grupo extends Persistencia{
	var $id = NULL;
	var $nomePacote;
	var $dataEmbarque;
	var $dataChegada;
	var $dataModificacao;
	var $valorPacote;
	var $valorTaxaEmbarque;
	var $valorAdesao;
	var $valorCusto;
	var $moeda = NULL;
	var $status = NULL;
	var $possuiPacoteOpcional;
	var $nomePacoteOpcional;
	var $valorPacoteOpcional;
	var $valorTaxaEmbarqueOpcional;
	var $valorAdesaoOpcional;
	var $valorCustoOpcional;
	var $roteiroAnexo;
	var $cotacaoCusto;
	var $ano;
	var $pautaAnexo;
	var $modeloContrato;
	var $plano;
	var $destino;
	var $modeloFicha;
	
	public function STATUS_ANDAMENTO(){
		return 1;
	}
	public function STATUS_FINALIZADO(){
		return 2;
	}
	public function STATUS_CANCELADO(){
		return 3;
	}
	
	
	public function migrarParticipantes(){
		$p = new Participante();
		$g = new Grupo;
		$gorigem = new Grupo;
		$oD = new Distribuicao();
		$idGrupo = $g->md5_decrypt($_REQUEST['idGrupoPara']);
		$idGrupoDe  = $g->md5_decrypt($_REQUEST['idGrupoDe']);
		$g->getById($idGrupo);
		$gorigem->getById($idGrupoDe);
		$usuario = new Usuario();
		$usuario->id = $_SESSION['ag_idUsuario'];
		$data = date("Y-m-d H:i:s");
		
		foreach($_REQUEST['participante'] as $key => $id){
		$p->getById($id);			
		$p->grupo = $g;
		$p->valorTotal = $g->getValorTotal($p->pacoteOpcional);
		$p->custoTotal = $g->getCustoTotal($p->pacoteOpcional);
		$p->save();
		$p->atualiza_status();
		
		//grava log de pagamento
		$oLog = new LogUsuario();		
		$movimento = "MIGRAR PARTICIPANTE<BR> CLIENTE: ".$p->cliente->nomeCompleto."<BR> GRUPO ORIGEM: ".$gorigem->nomePacote."<BR> GRUPO DESTINO: ".$g->nomePacote;
		$oLog->usuario = $usuario;
		$oLog->data = $data;
		$oLog->movimento = $movimento;
		$oLog->save();		
		//fim da log
		
		
		//remover distribuicao dos quartos		
		$oD->remover($id);		
		}
		$_SESSION['tupi.mensagem'] = 57;
		}
	
	public function gruposComDesistentesPeriodo($datai,$dataf){
	$sql = "select distinct g.* from ag_grupo g inner join ag_participante p on g.id = p.grupo where p.dataInscricao between '".$datai."' and '".$dataf."'";
	return $this->getSQL($sql);
	}
	
	public function gruposDeCliente($idCliente){
	$sql = "select g.* from ag_grupo g inner join ag_participante p on p.grupo = g.id and p.cliente = ".$idCliente." group by g.id";
	return $this->getSQL($sql);
	}
	
	public function gruposComPagamentoPeriodo($datai,$dataf){
	$sql = "select distinct g.* from ag_grupo g inner join ag_participante p on g.id = p.grupo inner join ag_pagamento pag on pag.participante = p.id where pag.bitCancelado = 0 and pag.dataPagamento between '".$datai."' and '".$dataf."' union select distinct g.* from ag_grupo g inner join ag_participante p on g.id = p.grupo inner join ag_pagamento pag on pag.participante = p.id inner join ag_cheque ch on ch.idPagamento = pag.id where pag.bitCancelado = 0 and ch.data between '".$datai."' and '".$dataf."'";
		return $this->getSQL($sql);
	}
	
	public function recuperaTotal($ano){
		$sql = "select count(id) as total from ag_grupo ";
		if($ano != "")
		$sql .= " where ano = ".$ano;
		$rs = $this->DAO_ExecutarQuery($sql);	
		return $this->DAO_Result($rs,"total",0);
	}	
	
	public function recuperaAnos(){
		$sql = "select ano from ag_grupo group by ano";
		$rs = $this->DAO_ExecutarQuery($sql);	
		return $rs;
	}	
	
	public function pesquisa($inicio,$fim,$ano){
	$sql = "select * from ag_grupo" ;
	if($ano != "")
		$sql .= " where ano = ".$ano;
	   $sql .= " order by dataEmbarque asc";
	  $sql .= " limit $inicio, $fim";
	 
	return $this->getSQL($sql);
	}
	
	public function recuperaTotalAndamento($pesquisa = ""){
		$sql = "select count(id) as total from ag_grupo where idStatus = ".$this->STATUS_ANDAMENTO();
		if($pesquisa != ''){
			$sql .= " and nomePacote Like '%".$pesquisa."%'";
		}
		$rs = $this->DAO_ExecutarQuery($sql);	
		return $this->DAO_Result($rs,"total",0);
	}	
	
	public function incluir(){
		$this->modeloContrato = $_POST['modeloContrato'];
		$this->modeloFicha=  $_POST['modeloFicha'];
		$this->nomePacote = $_POST['nomePacote'];
		$this->dataEmbarque = $this->convdata($_POST['dataEmbarque'],"ntm");
		$this->dataChegada = $this->convdata($_POST['dataChegada'],"ntm");
		$this->dataModificacao = date("Y-m-d");
		$this->destino = $_POST['destino'];
		$this->plano = $_POST['plano'];
		$this->valorPacote = $this->money($_POST['valorPacote']  == '' ? 0 : $_POST['valorPacote'],"bta");
		$this->valorTaxaEmbarque = $this->money($_POST['valorTaxaEmbarque'] == '' ? 0 : $_POST['valorTaxaEmbarque'],"bta");
		$this->valorAdesao = $this->money($_POST['valorAdesao'] == '' ? 0 : $_POST['valorAdesao'] ,"bta");
		$this->valorCusto = $this->money($_POST['valorCusto'] == '' ? 0 : $_POST['valorCusto'],"bta");
		$this->cotacaoCusto = $this->money($_POST['cotacaoCusto'] == '' ? 0 : $_POST['cotacaoCusto'],"bta");
		$this->ano = $_REQUEST['ano'];
		//uploadArquivo roteiro
		if($_FILES['roteiro']['name'] != ''){
			$nomeImagem = date("d_m_Y_H_i_s").$this->removerAcento($_FILES['roteiro']['name']);
			$diretorio = $this->URI."/docs/";		
			$this->uploadArquivo($_FILES['roteiro'],$nomeImagem,$diretorio);
			
			$this->roteiroAnexo = $nomeImagem;
		}
		//uploadArquivo pauta
		if($_FILES['pauta']['name'] != ''){
			$nomeImagem = date("d_m_Y_H_i_s").$this->removerAcento($_FILES['pauta']['name']);
			$diretorio = $this->URI."/docs/";		
			$this->uploadArquivo($_FILES['pauta'],$nomeImagem,$diretorio);
			
			$this->pautaAnexo = $nomeImagem;
		}
				
		if($_POST['nomePacoteOpcional'] != ''){
		$this->possuiPacoteOpcional = 1;
		$this->nomePacoteOpcional = $_POST['nomePacoteOpcional'];
		$this->valorPacoteOpcional = $this->money($_POST['valorPacoteOpcional']  == '' ? 0 : $_POST['valorPacoteOpcional'],"bta");
		$this->valorTaxaEmbarqueOpcional = $this->money($_POST['valorTaxaEmbarqueOpcional'] == '' ? 0 : $_POST['valorTaxaEmbarqueOpcional'],"bta");
		$this->valorAdesaoOpcional = $this->money($_POST['valorAdesaoOpcional'] == '' ? 0 : $_POST['valorAdesaoOpcional'],"bta");
		$this->valorCustoOpcional = $this->money($_POST['valorCustoOpcional'] == '' ? 0 : $_POST['valorCustoOpcional'],"bta");
		
		}else{
			$this->possuiPacoteOpcional = 0;
			$this->nomePacoteOpcional = "";
		$this->valorPacoteOpcional = 0;
		$this->valorTaxaEmbarqueOpcional = 0;
		$this->valorAdesaoOpcional = 0;
		$this->valorCustoOpcional = 0;
		}
		$oM = new Moeda();
		$oM->id = $_REQUEST['moeda'];
		$oE = new StatusGrupo();
		$oE->id = $_REQUEST['status'];
		$this->moeda = $oM;
		$this->status = $oE;
		$newid = $this->save();
		$_SESSION['tupi.mensagem'] = 20;	
		
		//registrar quartos novos:
		//quartos solteiros
		for($i = 1; $i < 6 ;$i++){
		$oQ = new Quarto();
		$oQ->capacidade  = 1;
		$oQ->numero = "I ".str_pad($i,2,"0",STR_PAD_LEFT);
		$oQ->grupo = $this;
		$oQ->save();
		}
		//quartos casais
		for($i = 1; $i < 11 ;$i++){
		$oQ = new Quarto();
		$oQ->capacidade  = 2;
		$oQ->numero = "C ".str_pad($i,2,"0",STR_PAD_LEFT);
		$oQ->grupo = $this;
		$oQ->save();
		}
		//quartos duplos
		for($i = 1; $i < 11 ;$i++){
		$oQ = new Quarto();
		$oQ->capacidade  = 2;
		$oQ->numero = "D ".str_pad($i,2,"0",STR_PAD_LEFT);
		$oQ->grupo = $this;
		$oQ->save();
		}
		
		//quartos duplos
		for($i = 1; $i < 4 ;$i++){
		$oQ = new Quarto();
		$oQ->capacidade  = 3;
		$oQ->numero = "T ".str_pad($i,2,"0",STR_PAD_LEFT);
		$oQ->grupo = $this;
		$oQ->save();
		}
		
		//registrar log
		$logGrupo = new LogGrupo();
		$user = new Usuario();
		$user->id = $_SESSION['ag_idUsuario'];
		$logGrupo->usuario = $user;
		$logGrupo->grupo = $this;
		$logGrupo->dataHora = date("Y-m-d H:i:s");
		$logGrupo->txtLog = 'Criação do Grupo';
		$logGrupo->save();		
		return $newid;
	}
	
	
	public function alterar(){
		$this->getById($_POST['id']);
		//grava a log com as alteacoes
		//grava log
		$logGrupo = new LogGrupo();
		$user = new Usuario();
		$user->id = $_SESSION['ag_idUsuario'];
		$logGrupo->usuario = $user;
		$logGrupo->grupo = $this;
		$logGrupo->dataHora = date("Y-m-d H:i:s");
		$logGrupo->txtLog = 'Alteração:';
		$logGrupo->txtLog .= $this->money($_POST['valorPacote'],"bta") == $this->valorPacote ? "" : "Valor do Pacote: ".$this->money($_POST['valorPacote'],"bta")."<br/>";
		$logGrupo->txtLog .= $this->money($_POST['valorTaxaEmbarque'],"bta")  == $this->valorTaxaEmbarque ? "" : "Valor taxa Embarque: ".$this->money($_POST['valorTaxaEmbarque'],"bta")."<br/>";
		$logGrupo->txtLog .= $this->money($_POST['valorAdesao'],"bta")  == $this->valorAdesao  ? "" : "Valor da Adesão: ".$this->money($_POST['valorAdesao'],"bta")."<br/>";
		
		$logGrupo->txtLog .= $this->money($_POST['valorPacoteOpcional'],"bta")  == $this->valorPacoteOpcional ? "" : "Valor do Pacote Opcional: ".$this->money($_POST['valorPacoteOpcional'],"bta")."<br/>";
		$logGrupo->txtLog .= $this->money($_POST['valorTaxaEmbarqueOpcional'],"bta")  == $this->valorTaxaEmbarqueOpcional ? "" : "Valor da Taxa de Embarque Opcional: ".$this->money($_POST['valorTaxaEmbarqueOpcional'],"bta")."<br/>";
		$logGrupo->txtLog .= $this->money($_POST['valorAdesaoOpcional'],"bta")  ==  $this->valorAdesaoOpcional ? "" : "Valor da Adesão Opcional: ".$this->money($_POST['valorAdesaoOpcional'],"bta")."<br/>";
		$logGrupo->save();		
		
		
		$this->modeloContrato = $_POST['modeloContrato'];
		$this->modeloFicha=  $_POST['modeloFicha'];
		$this->nomePacote = $_POST['nomePacote'];
		$this->dataEmbarque = $this->convdata($_POST['dataEmbarque'],"ntm");
		$this->dataChegada = $this->convdata($_POST['dataChegada'],"ntm");
		$this->dataModificacao = date("Y-m-d");
		$this->valorPacote = $this->money($_POST['valorPacote']  == '' ? 0 : $_POST['valorPacote'],"bta");
		$this->valorTaxaEmbarque = $this->money($_POST['valorTaxaEmbarque'] == '' ? 0 : $_POST['valorTaxaEmbarque'],"bta");
		$this->valorAdesao = $this->money($_POST['valorAdesao'] == '' ? 0 : $_POST['valorAdesao'] ,"bta");
		$this->valorCusto = $this->money($_POST['valorCusto'] == '' ? 0 : $_POST['valorCusto'],"bta");
		$this->cotacaoCusto = $this->money($_POST['cotacaoCusto'] == '' ? 0 : $_POST['cotacaoCusto'],"bta");
		$this->destino = $_POST['destino'];
		$this->plano = $_POST['plano'];
		$this->ano = $_REQUEST['ano'];
		//uploadArquivo
		if($_FILES['roteiro']['name'] != ''){
			$nomeImagem = date("d_m_Y_H_i_s").$this->removerAcento($_FILES['roteiro']['name']);
			$diretorio = $this->URI."/docs/";		
			unlink($diretorio.$this->roteiroAnexo);
			$this->uploadArquivo($_FILES['roteiro'],$nomeImagem,$diretorio);			
			$this->roteiroAnexo = $nomeImagem;
		}
		
		//uploadArquivo pauta
		if($_FILES['pauta']['name'] != ''){
			$nomeImagem = date("d_m_Y_H_i_s").$this->removerAcento($_FILES['pauta']['name']);
			$diretorio = $this->URI."/docs/";
			unlink($diretorio.$this->pautaAnexo);		
			$this->uploadArquivo($_FILES['pauta'],$nomeImagem,$diretorio);			
			$this->pautaAnexo = $nomeImagem;
		}
				
		if($_POST['nomePacoteOpcional'] != ''){
		$this->possuiPacoteOpcional = 1;
		$this->nomePacoteOpcional = $_POST['nomePacoteOpcional'];
		$this->valorPacoteOpcional = $this->money($_POST['valorPacoteOpcional']  == '' ? 0 : $_POST['valorPacoteOpcional'],"bta");
		$this->valorTaxaEmbarqueOpcional = $this->money($_POST['valorTaxaEmbarqueOpcional'] == '' ? 0 : $_POST['valorTaxaEmbarqueOpcional'],"bta");
		$this->valorAdesaoOpcional = $this->money($_POST['valorAdesaoOpcional'] == '' ? 0 : $_POST['valorAdesaoOpcional'],"bta");
		$this->valorCustoOpcional = $this->money($_POST['valorCustoOpcional'] == '' ? 0 : $_POST['valorCustoOpcional'],"bta");
		}else{
		$this->possuiPacoteOpcional = 0;
		$this->nomePacoteOpcional = "";
		$this->valorPacoteOpcional = 0;
		$this->valorTaxaEmbarqueOpcional = 0;
		$this->valorAdesaoOpcional = 0;
		$this->valorCustoOpcional = 0;
		}
		$oM = new Moeda();
		$oM->id = $_REQUEST['moeda'];
		$oE = new StatusGrupo();
		$oE->id = $_REQUEST['status'];
		$this->moeda = $oM;
		$this->status = $oE;
		$newid = $this->save();
		$_SESSION['tupi.mensagem'] = 21;	
		
		return $newid;
	}
	
	function excluir(){
		$idGrupoExc = $this->md5_Decrypt($_REQUEST['idGrupo']);
		$this->getById($idGrupoExc);
		if($this->validaParticipantesExclusao()){
			$this->deletaQuartos();
			$this->deletaVoos();
			$this->deletaPauta();
			$this->deletaLog();
			$this->delete($idGrupoExc);
			$_SESSION['tupi.mensagem'] = 22;			
		}else{
		$_SESSION['tupi.mensagem'] = 23;
		}
	}
	
	
	function validaParticipantesExclusao(){
		$sql = "select id from ag_participante where grupo = ".$this->id;
		$rs = $this->DAO_ExecutarQuery($sql);
		if($this->DAO_NumeroLinhas($rs) > 0)
		return false;
		else
		return true;
	}
	
	function validaQuartosExclusao(){
		$sql = "select id from ag_quarto where idGrupo = ".$this->id;
		$rs = $this->DAO_ExecutarQuery($sql);
		if($this->DAO_NumeroLinhas($rs) > 0)
		return false;
		else
		return true;
	}
	
	function validaVoosExclusao(){
		$sql = "select id from ag_voo where idGrupo = ".$this->id;
		$rs = $this->DAO_ExecutarQuery($sql);
		if($this->DAO_NumeroLinhas($rs) > 0)
		return false;
		else
		return true;
	}
	function deletaPauta(){
		$sql = "delete from ag_pautareuniao where idGrupo = ".$this->id;
		$this->DAO_ExecutarQuery($sql);	
		
	}
	
	function deletaQuartos(){
		$sql = "delete from ag_distribuicao where idQuarto in(select id from ag_quarto where idGrupo = ".$this->id.")";
		$this->DAO_ExecutarQuery($sql);	
		$sql = "delete from ag_quarto where idGrupo = ".$this->id;
		$this->DAO_ExecutarQuery($sql);	
		
	}
	function deletaLog(){
		$sql = "delete from ag_loggrupo where idGrupo = ".$this->id;
		$this->DAO_ExecutarQuery($sql);	
		
	}
	function deletaVoos(){
		$sql = "delete from ag_voo where idGrupo = ".$this->id;
		$this->DAO_ExecutarQuery($sql);	
		
	}
	
	
	
	function getValorTotal($opcional){
	$total = $this->valorPacote+$this->valorTaxaEmbarque+$this->valorAdesao;
	if($opcional == 1)
		$total += $this->valorPacoteOpcional+$this->valorTaxaEmbarqueOpcional+$this->valorAdesaoOpcional;
	return $total;
	}
	
	function getCustoTotal($opcional){
	$total = $this->valorCusto;
	if($opcional == 1)
		$total += $this->valorCustoOpcional;
	return $total;
	}
	
	function getValorTotalOpcional(){
		$total = $this->valorPacoteOpcional+$this->valorTaxaEmbarqueOpcional+$this->valorAdesaoOpcional;
	return $total;
	}

}
?>
