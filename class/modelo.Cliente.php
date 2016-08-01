<?php
class Cliente extends Persistencia{
	var $id = NULL;
	var $nomeCompleto;
	var $cpf;
	var $estadoCivil = NULL;
	var $dataNascimento;
	var $sexo;
	var $endereco;
	var $bairro;
	var $cep;
	var $telefoneResidencial;
	var $telefoneComercial;
	var $celular;
	var $fax;
	var $rg;
	var $orgaoEmissorRg;
	var $passaporte;
	var $dataEmissaoPassaporte;
	var $dataValidadePassaporte;
	var $orgaoExpedidorPassaporte;
	var $nomeCracha;
	var $tamanhoCamisa;
	var $problemasSaude;
	var $restricaoAlimentar;
	var $email;
	var $nacionalidade;
	var $cidadeEndereco;
	var $estadoEndereco;
	var $paisEndereco;
	var $cidadeNascimento;
	var $paisNascimento;
	var $estadoNascimento;
	var $preferencial;
	var $enviaCorrespondencia;
	
	public function recuperaTotal($busca){
		$rs = $this->DAO_ExecutarQuery("select count(id) as total from ag_cliente where cpf like '%$busca%' or nomeCompleto like '%$busca%'");	
		return $this->DAO_Result($rs,"total",0);
	}
	
	public function pesquisa($inicio,$fim,$busca){
	$sql = "select* from ag_cliente where cpf like '%$busca%' or nomeCompleto like '%$busca%' order by nomeCompleto limit $inicio, $fim";	
	return $this->getSQL($sql);
	}
	
	public function fidelizacao($quantidade){
	$sql = "select c.* from ag_participante p inner join ag_cliente c on c.id = p.cliente group by p.cliente having count(p.Cliente) >= ".$quantidade." order by count(p.Cliente)";	
	return $this->getSQL($sql);
	}
	
	
	public function pesquisaMalaDireta(){
	$sql = "select * from ag_cliente c ";	
	
	if($_REQUEST['idGrupo'] != ""){
	$sql .= " inner join ag_participante p on p.cliente = c.id";
	}
	$where = false;
	if($_REQUEST['dataValidadePassaporte'] != ""){
	$sql .= ($where ? " and " : " where " ). "c.dataValidadePassaporte <= '".$_REQUEST['dataValidadePassaporte']."' and c.dataValidadePassaporte != '0000-00-00' ";
	$where = true;
	}
	if($_REQUEST['sexo'] != ""){
	$sql .= ($where ? " and " : " where " ). "c.sexo = '".$_REQUEST['sexo']."' ";
	$where = true;
	}
	if($_REQUEST['estadoCivil'] != ""){
	$sql .= ($where ? " and " : " where " ). "c.estadoCivil = '".$_REQUEST['estadoCivil']."' ";
	$where = true;
	}
	if($_REQUEST['pais'] != ""){
	$sql .= ($where ? " and " : " where " ). "c.txtpaisEndereco = '".$_REQUEST['pais']."' ";
	$where = true;
	}
	if($_REQUEST['estado'] != ""){
	$sql .= ($where ? " and " : " where " ). "c.txtestadoEndereco = '".$_REQUEST['estado']."' ";
	$where = true;
	}
	if($_REQUEST['cidade'] != ""){
	$sql .= ($where ? " and " : " where " ). "c.txtcidadeEndereco = '".$_REQUEST['cidade']."' ";
	$where = true;
	}
	if($_REQUEST['nome'] != ""){
	$sql .= ($where ? " and " : " where " ). "c.nomeCompleto like '%".$_REQUEST['nome']."%' ";
	$where = true;
	}
    if($_REQUEST['cpf'] != ""){
    $sql .= ($where ? " and " : " where " ). "c.cpf like '".$_REQUEST['cpf']."%' ";
    $where = true;
    }
	
	if($_REQUEST['mesAniversario'] != ""){
	$sql .= ($where ? " and " : " where " ). "MONTH(c.dataNascimento) = '".$_REQUEST['mesAniversario']."' ";
	$where = true;
	}
	
	if($_REQUEST['idGrupo'] != ""){
	$sql .= ($where ? " and " : " where " ). "p.grupo = ".$this->md5_decrypt($_REQUEST['idGrupo'])." and p.status != 3 ";
	$where = true;
	}

	$sql .= ($where ? " and " : " where " ). "c.bitEnviaCorrespondencia = 1 ";


	$sql .= " order by c.nomeCompleto";
	return $this->getSQL($sql);
	}
	
	public function pesquisaPassaportesVencer(){
	$hoje = date("Y-m-d");
	$hojets = strtotime($hoje);
	$primeiroDia = date("Y-m-d", mktime(0,0,0,date("m",$hojets)+2,1,date("Y",$hojets)));
	$mests = strtotime($primeiroDia);
	$ultimoDia = date("Y-m-d", mktime(0,0,0,date("m",$hojets)+2,date("t",$mests),date("Y",$hojets)));		
	$sql  ="SELECT * FROM `ag_cliente` where dataValidadePassaporte between '$primeiroDia' and '$ultimoDia' and email = ''";
	return $this->getSQL($sql);
	}
	
	public function alterar(){
		if($this->isCPFRepetido($this->limpaDigitos($_POST['cpf']),$_POST['id'])){
			$_SESSION['tupi.mensagem'] = 16;
			header("Location:cadastro.clienteEdita.php?idCliente=".$this->md5_encrypt($_POST['id']));
			exit();
		}else{
			$this->getById($_POST['id']);
			$this->nomeCompleto = $_POST['nomeCompleto'];
			$this->cpf = $this->limpaDigitos($_POST['cpf']);
			$this->dataNascimento = $this->convdata($_POST['dataNascimento'],"ntm");
			$this->sexo = $_POST['sexo'];
			$this->preferencial = $_POST['preferencial'];
			$this->endereco = $_POST['endereco'];
			$this->bairro = $_POST['bairro'];
			$this->cep = $_POST['cep'];
			$this->telefoneResidencial = $_POST['telefoneResidencial'];
			$this->telefoneComercial = $_POST['telefoneComercial'];
			$this->celular = $_POST['celular'];
			$this->fax = $_POST['fax'];
			$this->rg = $_POST['rg'];
			$this->orgaoEmissorRg = $_POST['orgaoEmissorRg'];
			$this->passaporte = $_POST['passaporte'];
			$this->dataEmissaoPassaporte = $this->convdata($_POST['dataEmissaoPassaporte'],"ntm");
			$this->dataValidadePassaporte = $this->convdata($_POST['dataValidadePassaporte'],"ntm");
			$this->orgaoExpedidorPassaporte = $_POST['orgaoExpedidorPassaporte'];
			$this->nomeCracha = $_POST['nomeCracha'];
			$this->tamanhoCamisa = $_POST['tamanhoCamisa'];
			$this->problemasSaude = $_POST['problemasSaude'];
			$this->enviaCorrespondencia = $_POST['enviaCorrespondencia'];
			//$this->restricaoAlimentar = $_POST['restricaoAlimentar'];
			$this->email = $_POST['email'];
			$this->nacionalidade = $_POST['nacionalidade'];	
			$this->paisNascimento = $_POST['paisNascimento'];
			$this->estadoNascimento = $_POST['estadoNascimento'];
			$this->cidadeNascimento = $_POST['cidadeNascimento'];
			$this->paisEndereco = $_POST['paisEndereco'];
			$this->estadoEndereco = $_POST['estadoEndereco'];
			$this->cidadeEndereco = $_POST['cidadeEndereco'];
			
			if($_REQUEST['restricaoAlimentar'] == '1'){
			for($ira=1;$ira < 22; $ira++){
			if(isset($_REQUEST['ra'.$ira]))
			$this->restricaoAlimentar .= $_REQUEST['ra'.$ira];
			}
			}else{
			$this->restricaoAlimentar = '';
			}
			
			$oEstadoCivil = new EstadoCivil();
			$oEstadoCivil->id = $_REQUEST['estadoCivil'];
			$this->estadoCivil = $oEstadoCivil;
			
			$_SESSION['tupi.mensagem'] = 18;
			
			
			//grava log de pagamento
		$oLog = new LogUsuario();
		$usuario = new Usuario();
		$usuario->id = $_SESSION['ag_idUsuario'];
		$data = date("Y-m-d H:i:s");
		$movimento = "ALTERAR CLIENTE<BR> CLIENTE: ".$this->nomeCompleto."<BR> CPF: ".$this->cpf;
		$oLog->usuario = $usuario;
		$oLog->data = $data;
		$oLog->movimento = $movimento;
		$oLog->save();		
		//fim da log
			
			$id = $this->save();
			header("Location:cadastro.clienteEdita.php?idCliente=".$this->md5_encrypt($_POST['id']));
			exit();	
			return $this->id;
		}
	}
	
	public function alterarb(){
		if($this->isCPFRepetido($this->limpaDigitos($_POST['cpf']),$_POST['id'])){
			$_SESSION['tupi.mensagem'] = 16;
			header("Location:cadastro.clienteEdita.php?idCliente=".$this->md5_encrypt($_POST['id']));
			exit();
		}else{
			$this->getById($_POST['id']);
			$this->nomeCompleto = $_POST['nomeCompleto'];
			$this->cpf = $this->limpaDigitos($_POST['cpf']);
			$this->dataNascimento = $this->convdata($_POST['dataNascimento'],"ntm");
			$this->sexo = $_POST['sexo'];
			$this->preferencial = $_POST['preferencial'];
			$this->endereco = $_POST['endereco'];
			$this->bairro = $_POST['bairro'];
			$this->cep = $_POST['cep'];
			$this->telefoneResidencial = $_POST['telefoneResidencial'];
			$this->telefoneComercial = $_POST['telefoneComercial'];
			$this->celular = $_POST['celular'];
			$this->fax = $_POST['fax'];
			$this->rg = $_POST['rg'];
			$this->orgaoEmissorRg = $_POST['orgaoEmissorRg'];
			$this->passaporte = $_POST['passaporte'];
			$this->dataEmissaoPassaporte = $this->convdata($_POST['dataEmissaoPassaporte'],"ntm");
			$this->dataValidadePassaporte = $this->convdata($_POST['dataValidadePassaporte'],"ntm");
			$this->orgaoExpedidorPassaporte = $_POST['orgaoExpedidorPassaporte'];
			$this->nomeCracha = $_POST['nomeCracha'];
			$this->tamanhoCamisa = $_POST['tamanhoCamisa'];
			$this->problemasSaude = $_POST['problemasSaude'];
			$this->enviaCorrespondencia = $_POST['enviaCorrespondencia'];
			//$this->restricaoAlimentar = $_POST['restricaoAlimentar'];
			$this->email = $_POST['email'];
			$this->nacionalidade = $_POST['nacionalidade'];	
			$this->paisNascimento = $_POST['paisNascimento'];
			$this->estadoNascimento = $_POST['estadoNascimento'];
			$this->cidadeNascimento = $_POST['cidadeNascimento'];
			$this->paisEndereco = $_POST['paisEndereco'];
			$this->estadoEndereco = $_POST['estadoEndereco'];
			$this->cidadeEndereco = $_POST['cidadeEndereco'];
			
			if($_REQUEST['restricaoAlimentar'] == '1'){
			for($ira=1;$ira < 22; $ira++){
			if(isset($_REQUEST['ra'.$ira]))
			$this->restricaoAlimentar .= $_REQUEST['ra'.$ira];
			}
			}else{
			$this->restricaoAlimentar = '';
			}
			
			$oEstadoCivil = new EstadoCivil();
			$oEstadoCivil->id = $_REQUEST['estadoCivil'];
			$this->estadoCivil = $oEstadoCivil;
			
			
			
			
			//grava log de pagamento
		$oLog = new LogUsuario();
		$usuario = new Usuario();
		$usuario->id = $_SESSION['ag_idUsuario'];
		$data = date("Y-m-d H:i:s");
		$movimento = "ALTERAR CLIENTE<BR> CLIENTE: ".$this->nomeCompleto."<BR> CPF: ".$this->cpf;
		$oLog->usuario = $usuario;
		$oLog->data = $data;
		$oLog->movimento = $movimento;
		$oLog->save();		
		//fim da log
			
			$id = $this->save();
			return $this->id;
		}
	}
	
	public function incluir(){
		if($this->isCPFRepetido($this->limpaDigitos($_POST['cpf']),0)){
			$_SESSION['tupi.mensagem'] = 16;
		}else{
	$this->nomeCompleto = $_POST['nomeCompleto'];
	$this->cpf = $this->limpaDigitos($_POST['cpf']);
	$this->dataNascimento = $this->convdata($_POST['dataNascimento'],"ntm");
	$this->sexo = $_POST['sexo'];
	$this->preferencial = $_POST['preferencial'];
	$this->endereco = $_POST['endereco'];
	$this->bairro = $_POST['bairro'];
	$this->cep = $_POST['cep'];
	$this->telefoneResidencial = $_POST['telefoneResidencial'];
	$this->telefoneComercial = $_POST['telefoneComercial'];
	$this->celular = $_POST['celular'];
	$this->fax = $_POST['fax'];
	$this->rg = $_POST['rg'];
	$this->orgaoEmissorRg = $_POST['orgaoEmissorRg'];
	$this->passaporte = $_POST['passaporte'];
	$this->dataEmissaoPassaporte = $this->convdata($_POST['dataEmissaoPassaporte'],"ntm");
	$this->dataValidadePassaporte = $this->convdata($_POST['dataValidadePassaporte'],"ntm");
	$this->orgaoExpedidorPassaporte = $_POST['orgaoExpedidorPassaporte'];
	$this->nomeCracha = $_POST['nomeCracha'];
	$this->tamanhoCamisa = $_POST['tamanhoCamisa'];
	$this->problemasSaude = $_POST['problemasSaude'];
	$this->enviaCorrespondencia = $_POST['enviaCorrespondencia'];
	//$this->restricaoAlimentar = $_POST['restricaoAlimentar'];
	if($_REQUEST['restricaoAlimentar'] == '1'){
			for($ira=1;$ira < 22; $ira++){
			if(isset($_REQUEST['ra'.$ira]))
			$this->restricaoAlimentar .= $_REQUEST['ra'.$ira];
			}
			}else{
			$this->restricaoAlimentar = '';
			}
	
	
	$this->email = $_POST['email'];
	$this->nacionalidade = $_POST['nacionalidade'];
	$this->paisNascimento = $_POST['paisNascimento'];
			$this->estadoNascimento = $_POST['estadoNascimento'];
			$this->cidadeNascimento = $_POST['cidadeNascimento'];
			$this->paisEndereco = $_POST['paisEndereco'];
			$this->estadoEndereco = $_POST['estadoEndereco'];
			$this->cidadeEndereco = $_POST['cidadeEndereco'];
	$oEstadoCivil = new EstadoCivil();
	$oEstadoCivil->id = $_REQUEST['estadoCivil'];
	$this->estadoCivil = $oEstadoCivil;
	$_SESSION['tupi.mensagem'] = 17;
	
	//grava log de pagamento
		$oLog = new LogUsuario();
		$usuario = new Usuario();
		$usuario->id = $_SESSION['ag_idUsuario'];
		$data = date("Y-m-d H:i:s");
		$movimento = "INCLUIR CLIENTE<BR> CLIENTE: ".$this->nomeCompleto."<BR> CPF: ".$this->cpf;
		$oLog->usuario = $usuario;
		$oLog->data = $data;
		$oLog->movimento = $movimento;
		$oLog->save();		
		//fim da log
		
	return $this->save();
		
		}
	}
	
	public function excluir(){	
		$this->getById($this->md5_Decrypt($_REQUEST['idCliente']));
		if($this->validaParticipantesExclusao()){
			if($this->validaEmissorChequeExclusao()){
					$this->delete($this->md5_Decrypt($_REQUEST['idCliente']));
					$_SESSION['tupi.mensagem'] = 19;
					//grava log de pagamento
					$oLog = new LogUsuario();
					$usuario = new Usuario();
					$usuario->id = $_SESSION['ag_idUsuario'];
					$data = date("Y-m-d H:i:s");
					$movimento = "EXCLUIR CLIENTE<BR> CLIENTE: ".$this->nomeCompleto."<BR> CPF: ".$this->cpf;
					$oLog->usuario = $usuario;
					$oLog->data = $data;
					$oLog->movimento = $movimento;
					$oLog->save();		
					//fim da log	
			}else{
				$_SESSION['tupi.mensagem'] = 51;
			}
		}else{
		$_SESSION['tupi.mensagem'] = 50;
		}
	
	}
	
	function validaParticipantesExclusao(){
		$sql = "select id from ag_participante where cliente = ".$this->id;
		$rs = $this->DAO_ExecutarQuery($sql);
		if($this->DAO_NumeroLinhas($rs) > 0)
		return false;
		else
		return true;
	}
	
	function validaEmissorChequeExclusao(){
		$sql = "select id from ag_pagamento where emissorCheque = ".$this->id." union select id from ag_cheque where emissor = ".$this->id;
		$rs = $this->DAO_ExecutarQuery($sql);
		if($this->DAO_NumeroLinhas($rs) > 0)
		return false;
		else
		return true;
	}
	
	public function isCPFRepetido($cpf,$idCliente = 0){
		$sql = "select cpf from ag_cliente where email = '$cpf' and id != $idCliente";
		$rs = $this->getSQL($sql);
		if (count($rs) > 0)
		return true;
		else
		return false;		
	}
	
	function getByCpf($cpf){
	$sql = "select * from ag_cliente where cpf = '".$cpf."'";
	$rs = $this->getSQL($sql);
	if (count($rs) > 0){
	$this->getById($rs[0]->id);
	return true;
	}else{
	return false;
	}
	}
	function getByCpfNome($cpf,$nome){
	if($cpf != "")
	$sql = "select * from ag_cliente where cpf = '".$cpf."'";
	else
	$sql = "select * from ag_cliente where nomeCompleto = '".$nome."'";
	$rs = $this->getSQL($sql);
	if (count($rs) > 0){
	$this->getById($rs[0]->id);
	return true;
	}else{
	return false;
	}
	}
	
	function listaCidades(){
	$lista = "";
	$sql = "select txtcidadeEndereco as cidade from ag_cliente group by txtcidadeEndereco union select txtcidadeNascimento as cidade from ag_cliente group by txtcidadeNascimento ";
	$rs = $this->DAO_ExecutarQuery($sql);
	while($row = $this->DAO_GerarArray($rs)){
	$lista .= "'".$row['cidade']."',";
	}
	return substr($lista,0,strlen($lista)-1);
	}

	function listaPaises(){
	$lista = "";
	$sql = "select txtpaisEndereco as pais from ag_cliente group by txtpaisEndereco union select txtpaisNascimento as pais from ag_cliente group by txtpaisNascimento ";
	$rs = $this->DAO_ExecutarQuery($sql);
	while($row = $this->DAO_GerarArray($rs)){
	$lista .= "'".$row['pais']."',";
	}
	return substr($lista,0,strlen($lista)-1);
	}
	
	function listaEstados(){
	$lista = "";
	$sql = "select txtestadoEndereco as estado from ag_cliente group by txtestadoEndereco union select txtestadoNascimento as estado from ag_cliente group by txtestadoNascimento ";
	$rs = $this->DAO_ExecutarQuery($sql);
	while($row = $this->DAO_GerarArray($rs)){
	$lista .= "'".$row['estado']."',";
	}
	return substr($lista,0,strlen($lista)-1);
	}
	function listaNomes(){
	$lista = "";
	$sql = "select nomeCompleto as nome from ag_cliente group by nomeCompleto";
	$rs = $this->DAO_ExecutarQuery($sql);
	while($row = $this->DAO_GerarArray($rs)){
	$lista .= "'".$row['nome']."',";
	}
	return substr($lista,0,strlen($lista)-1);
	}
	
	function listaNomesIds(){
	$lista = "";
	$sql = "select nomeCompleto as nome, id from ag_cliente group by nomeCompleto";
	$rs = $this->DAO_ExecutarQuery($sql);
	while($row = $this->DAO_GerarArray($rs)){
	$lista .= "'".$row['nome']."-".$row['id']."',";
	}
	return substr($lista,0,strlen($lista)-1);
	}
	
	function listaCPFs(){
	$lista = "";
	$sql = "select cpf as cpf from ag_cliente group by cpf";
	$rs = $this->DAO_ExecutarQuery($sql);
	while($row = $this->DAO_GerarArray($rs)){
	$lista .= "'".$row['cpf']."',";
	}
	return substr($lista,0,strlen($lista)-1);
	}

	
	function idade(){
		//calculo timestam das duas datas
		$timestamp1 = mktime(0,0,0,date("m"),date("d"),date("Y"));
		$timestamp2 = strtotime($this->dataNascimento);
 
		//diminuo a uma data a outra
		$segundos_diferenca = $timestamp1 - $timestamp2;
		//echo $segundos_diferenca;
		 
		//converto segundos em dias
		$dias_diferenca = $segundos_diferenca / (60 * 60 * 24 * 365);
		 
		//obtenho o valor absoluto dos dias (tiro o possível sinal negativo)
		$dias_diferenca = abs($dias_diferenca);
		 
		//tiro os decimais aos dias de diferenca
		$dias_diferenca = floor($dias_diferenca);
 		return $dias_diferenca;
	}
	
	

}
?>
