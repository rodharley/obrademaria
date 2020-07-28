<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 13;
include("tupi.seguranca.php");
$tpl->BREADCRUMB = '    <ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="grupos.andamento.php">Grupos</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="participante.lista.php?idGrupo='.$_REQUEST['idGrupo'].'">Participantes</a> <span class="divider">/</span>
    </li>

    <li class="active">Cadastrar Participante</li>
    </ul>';

$oG = new Grupo();
$idGrupo = $oG->md5_decrypt($_REQUEST['idGrupo']);
$oG->getById($idGrupo);
$tpl->NOME_GRUPO = $oG->nomePacote;

$oQuarto = new Quarto();
$oCliente = new Cliente();
$oUf = new Uf();
$oCidade = new cidade();
$oEstCivil = new EstadoCivil();
$oPartic = new Participante();

$idGrupo = $oCliente->md5_decrypt($_REQUEST['idGrupo']);
$oG->getById($idGrupo);
$tpl->NOME_PACOTE = $oG->nomePacote;
if($oG->possuiPacoteOpcional == 1){
$tpl->NOME_PACOTE_OPCIONAL = $oG->nomePacoteOpcional;
$tpl->block("BLOCK_PACOTE_OPCIONAL");
}
$tpl->ID_GRUPO_HASH = $_REQUEST['idGrupo'];
$tpl->ACAO = "Incluir";
$tpl->ID_GRUPO = $_REQUEST['idGrupo'];
$tpl->dt_inscr = date("d/m/Y");
$idUf = 0;
$idCidade = 0;
$idUfEndereco = 0;
$idCidadeEndereco = 0;
$idEstadoCivil = 0;
$rsec = $oEstCivil->getRows();
$rsUf = $oUf->getRows();
$tpl->CHCK_CORRESPONDENCIA_NAO = '';
$tpl->CHCK_CORRESPONDENCIA_SIM = 'checked="checked"';
//recuperacao do cliente
if(isset($_REQUEST['cpf'])){
	if($oPartic->getByCpfNomeCliente($oCliente->limpaDigitos($_REQUEST['cpf']),$_REQUEST['nome'],$idGrupo)){
		$tpl->COMENTARIO = "Cliente já está cadastrado como participante nesse grupo!";
			$tpl->TIPO_COMENTARIO = "error";
			$tpl->CPF = "";
			$tpl->block("BLOCK_CONSULTA");
	}else{
		if($oCliente->getByCpfNome($oCliente->limpaDigitos($_REQUEST['cpf']),$_REQUEST['nome'])){	
			//ids dos relacionamentos	
			$idUf = $oCliente->cidadeNascimento->uf->id;
			$idCidade = $oCliente->cidadeNascimento->id;
			$idUfEndereco = $oCliente->cidade->uf->id;
			$idCidadeEndereco = $oCliente->cidade->id;
			$idEstadoCivil = $oCliente->estadoCivil->id;
			$tpl->block("BLOCK_CONSULTA");
			$tpl->COMENTARIO = "Cliente já cadastrado na base.";
			$tpl->TIPO_COMENTARIO = "success";		
			$tpl->NOME = $oCliente->nomeCompleto;
			$tpl->EMAIL = $oCliente->email;
			$tpl->NACIONALIDADE = $oCliente->nacionalidade;
			$tpl->CPF = $oCliente->cpf;
			$tpl->NOME_CRACHA = $oCliente->nomeCracha;
			$tpl->TAMANHO_CAMISA = $oCliente->tamanhoCamisa;
			$tpl->RG = $oCliente->rg;
			$tpl->ORGAORG = $oCliente->orgaoEmissorRg;
			$tpl->PASSAPORTE = $oCliente->passaporte;
			$tpl->ORGAO_PASSAPORTE = $oCliente->orgaoExpedidorPassaporte;
			$tpl->DATA_EMISSAO_PASSAPORTE = $oCliente->convdata($oCliente->dataEmissaoPassaporte,"mtn");
			$tpl->DATA_VALIDADE_PASSAPORTE = $oCliente->convdata($oCliente->dataValidadePassaporte,"mtn");
			$tpl->DATA_NASCIMENTO = $oCliente->convdata($oCliente->dataNascimento,"mtn");
			$tpl->ENDERECO = $oCliente->endereco;
			$tpl->CEP = $oCliente->cep;
			$tpl->BAIRRO = $oCliente->bairro;
			$tpl->TEL_RESIDENCIAL = $oCliente->telefoneResidencial;
			$tpl->TEL_COMERCIAL = $oCliente->telefoneComercial;
			$tpl->CELULAR = $oCliente->celular;
			$tpl->FAX = $oCliente->fax;
			$tpl->PROBLEMAS_SAUDE = $oCliente->problemasSaude;
			$tpl->RESTRICAO_ALIMENTAR = $oCliente->restricaoAlimentar;
			$tpl->PAIS_NASCIMENTO = $oCliente->paisNascimento;
			$tpl->ESTADO_NASCIMENTO = $oCliente->estadoNascimento;
			$tpl->CIDADE_NASCIMENTO = $oCliente->cidadeNascimento;
			$tpl->PAIS_ENDERECO = $oCliente->paisEndereco;
			$tpl->ESTADO_ENDERECO = $oCliente->estadoEndereco;
			$tpl->CIDADE_ENDERECO = $oCliente->cidadeEndereco;
			$tpl->ID_CLIENTE = $oCliente->id;
			
			if($oCliente->sexo == "M")
			$tpl->CHECKED_SEXOM = 'selected';
			else
			$tpl->CHECKED_SEXOF = 'selected';
			
			if($oCliente->preferencial == 0)
			$tpl->CHECKED_PREFN = 'selected';
			else
			$tpl->CHECKED_PREFS = 'selected';
			
			if ($oCliente->enviaCorrespondencia == 0){
				$tpl->CHCK_CORRESPONDENCIA_NAO = 'checked="checked"';
				$tpl->CHCK_CORRESPONDENCIA_SIM = '';
			}else{
				$tpl->CHCK_CORRESPONDENCIA_NAO = ''; 
				$tpl->CHCK_CORRESPONDENCIA_SIM = 'checked="checked"';
			}
			
		}else{
			$tpl->COMENTARIO = "Novo Cliente, preencher todos os dados.";
			$tpl->TIPO_COMENTARIO = "warning";
			$tpl->CPF = $_REQUEST['cpf'];
			$tpl->NOME	 = $_REQUEST['nome'];
				
		}
	}
}else{
$tpl->block("BLOCK_CONSULTA");
}

$tpl->CIDADES = $oCliente->listaCidades();
$tpl->PAISES = $oCliente->listaPaises();
$tpl->ESTADOS = $oCliente->listaEstados();
$tpl->NOMES = $oCliente->listaNomes();
$tpl->CPFS = $oCliente->listaCPFs();
//estado civil
foreach($rsec as $key => $ec){
$tpl->ID_ESTCIVIL = $ec->id;
$tpl->LABEL_ESTCIVIL = $ec->descricao;	
if($idEstadoCivil == $ec->id){
	$tpl->SELECTED_ESTCIVIL = "selected";	
}
$tpl->block("BLOCK_ESTADOCIVIL");
$tpl->clear("SELECTED_ESTCIVIL");	
}

//quartos
$oD = new Distribuicao();
$rsq = $oQuarto->getRows(0,999,array("capacidade"=>"asc","numero"=>"asc"),array("grupo"=>"=".$idGrupo));
foreach($rsq as $key => $q){
$rsd = $oD->getRows(0,999,array("id"=>"asc"),array("quarto"=>" = ".$q->id));
if($q->capacidade > count($rsd)){
$tpl->QUARTO_ID =  $q->id;
$tpl->QUARTO_LABEL = $q->numero." - Capacidade: ".$q->capacidade. " Pessoas - Ocupação:".count($rsd);	
$tpl->block("BLOCK_QUARTO");
}
}

include("tupi.template.finalizar.php"); 
?>