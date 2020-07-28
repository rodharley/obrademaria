<?php 
include("tupi.inicializar.php"); 
$codTemplate = "relatorio";
include("tupi.template.inicializar.php"); 
$codAcesso = 10;
include("tupi.seguranca.php");
//configura o grupo na pagina
$oParticipante = new Participante();
$oCidade = new Cidade();
$oGrupo = new Grupo();
$oQuarto = new Quarto();
$oD = new Distribuicao();
$idGrupo = $oGrupo->md5_decrypt($_REQUEST['idGrupo']);
$oGrupo->getById($idGrupo);

$tpl->ID_GRUPO_HASH = $_REQUEST['idGrupo'];
$tpl->ID_PARTICIPANTE_HASH = $_REQUEST['idParticipante'];




$tpl->PACOTE = $oGrupo->nomePacote;
$tpl->VALOR = $oGrupo->money($oGrupo->valorPacote+$oGrupo->valorTaxaEmbarque+$oGrupo->valorAdesao,"atb");
if($oGrupo->possuiPacoteOpcional == 1){
$tpl->OPCIONAL = $oGrupo->nomePacoteOpcional;
$tpl->VALOR_OPCIONAL = $oGrupo->money($oGrupo->valorPacoteOpcional+$oGrupo->valorTaxaEmbarqueOpcional+$oGrupo->valorAdesaoOpcional,"atb");
$tpl->block("BLOCK_OPCIONAL");
}

if(isset($_REQUEST['idParticipante'])){
$oParticipante->getById( $oGrupo->md5_decrypt($_REQUEST['idParticipante']));
$cliente = $oParticipante->cliente;

$tpl->NOME = $cliente->nomeCompleto;
$tpl->DATA_NASC = $oGrupo->convdata($cliente->dataNascimento,"mtn");
$tpl->ESTADO_CIVIL = $cliente->estadoCivil->descricao;
$tpl->CIDADE_NASC = $cliente->cidadeNascimento;
$tpl->UF_NASC = $cliente->estadoNascimento;
$tpl->PAIS_NASC = $cliente->paisNascimento;
$tpl->ENDERECO = $cliente->endereco;
$tpl->BAIRRO = $cliente->bairro;
$tpl->UF = $cliente->estadoEndereco;
$tpl->CEP = $cliente->cep;
$tpl->CIDADE = $cliente->cidadeEndereco;
$tpl->PAIS = $cliente->paisEndereco;
$tpl->TEL_RES = $cliente->telefoneResidencial;
$tpl->TEL_TRAB = $cliente->telefoneComercial;
$tpl->CELULAR = $cliente->celular;
$tpl->FAX = $cliente->fax;
$tpl->EMAIL = $cliente->email;
$tpl->IDENTIDADE = $cliente->rg;
$tpl->PASSAPORTE = $cliente->passaporte;
$tpl->ORGAO_EMISSOR_RG = $cliente->orgaoEmissorRg;
$tpl->DATA_EMISSAO_PS = $oGrupo->convdata($cliente->dataEmissaoPassaporte,"mtn");
$tpl->CPF = $oGrupo->formataCPFCNPJ($cliente->cpf);
$tpl->VALIDADE_PS = $oGrupo->convdata($cliente->dataValidadePassaporte,"mtn");
$tpl->ORGAO_EMISSOR_PS = $cliente->orgaoExpedidorPassaporte;
$tpl->NOME_CRACHA = $cliente->nomeCracha;
$tpl->TAMANHO_CAMISA = $cliente->tamanhoCamisa;
$tpl->SAUDE = $cliente->problemasSaude;
$tpl->ALIMENTOS = $cliente->restricaoAlimentar;
//quarto e companheiros
$rsd = $oD->getRows(0,999,array("id"=>"asc"),array("participante"=>" = ".$oParticipante->id));
if(count($rsd) > 0) {
	$tpl->QUARTO = $rsd[0]->quarto->numero;
	$idQuarto = $rsd[0]->quarto->id;
	$rsd = $oD->getRows(0,999,array("id"=>"asc"),array("quarto"=>" = ".$idQuarto));
	$listaCompanheiros = "";
	foreach ($rsd as $key => $distri){
		if($distri->participante->id != $oParticipante->id){
			$listaCompanheiros .= $distri->participante->cliente->nomeCompleto."<br/>";	
		}
	}
	$tpl->COMPANHEIROS = $listaCompanheiros;
}
}
if(!isset($_REQUEST['tupiSendEmail']))
$tpl->block("BLOCK_ENVIO_EMAIL");

include("tupi.template.finalizar.php"); 
?>