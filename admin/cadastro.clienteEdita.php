<?php 
include("tupi.inicializar.php"); 
$codTemplate = "tpl_nomenu";
include("tupi.template.inicializar.php"); 
$codAcesso = 5;
include("tupi.seguranca.php");
$tpl->BREADCRUMB = '    <ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="#">Cadastro</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="cadastro.clientes.php">Clientes</a> <span class="divider">/</span>
    </li>

    <li class="active">Editar Cliente</li>
    </ul>';
//recuperacao do cliente
$oCliente = new Cliente();
$oGrupo = new Grupo();
$tpl->ACAO = "Incluir";
$idEstadoCivil = 0;
$oEstCivil = new EstadoCivil();
$rsec = $oEstCivil->getRows();

$tpl->DISPLAY_RA = 'none';
$tpl->SELECTED_NAO_RA = ' selected ';
$tpl->SELECTED_SIM_RA = '';
$tpl->CHCK_CORRESPONDENCIA_NAO = '';
$tpl->CHCK_CORRESPONDENCIA_SIM = 'checked="checked"';

if(isset($_REQUEST['idCliente'])){
$oCliente->getById($oCliente->md5_Decrypt($_REQUEST['idCliente']));	

$idEstadoCivil = $oCliente->estadoCivil->id;


$tpl->NOME = $oCliente->nomeCompleto;
$tpl->EMAIL = $oCliente->email;
$tpl->NACIONALIDADE = $oCliente->nacionalidade;
$tpl->CPF = $oCliente->cpf;
$tpl->NOME_CRACHA = $oCliente->nomeCracha;
$tpl->TAMANHO_CAMISA = $oCliente->tamanhoCamisa;
$tpl->RG = $oCliente->rg;
$tpl->ORGAORG = $oCliente->orgaoEmissorRg;
$tpl->PASSAPORTE = $oCliente->passaporte;
$tpl->IMAGEM_PASSAPORTE = $oCliente->imagePassaporte;
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

if ($oCliente->enviaCorrespondencia == 0){
	$tpl->CHCK_CORRESPONDENCIA_NAO = 'checked="checked"';
	$tpl->CHCK_CORRESPONDENCIA_SIM = '';
}else{
	$tpl->CHCK_CORRESPONDENCIA_NAO = ''; 
	$tpl->CHCK_CORRESPONDENCIA_SIM = 'checked="checked"';
}
//$tpl->RESTRICAO_ALIMENTAR = $oCliente->restricaoAlimentar;
if(strpos($oCliente->restricaoAlimentar,'1. AVML  -  refeição vegetariana asiática') !== false)
$tpl->CHECKED_RA1 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'2. BBML - refeição de bebê') !== false)
$tpl->CHECKED_RA2 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'3. BLML - refeição suave - baixo teor de gordura e fibras') !== false)
$tpl->CHECKED_RA3 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'4. CHML - refeição de criança') !== false)
$tpl->CHECKED_RA4 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'5. DBML - refeição diabética') !== false)
$tpl->CHECKED_RA5 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'6. FPML - refeição de fruta') !== false)
$tpl->CHECKED_RA6 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'7. GFML - refeição sem glúten') !== false)
$tpl->CHECKED_RA7 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'8. HFML - refeição rica em fibras') !== false)
$tpl->CHECKED_RA8 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'9. HNML - refeição hindu') !== false)
$tpl->CHECKED_RA9 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'10. KSML - refeição kosher  (preparada de acordo com a dieta judaica)') !== false)
$tpl->CHECKED_RA10 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'11. LCML - refeição com baixo teor de calorias') !== false)
$tpl->CHECKED_RA11 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'12. LFML - refeição com baixo teor de colesterol') !== false)
$tpl->CHECKED_RA12 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'13. LPML - refeição com baixo teor de proteínas') !== false)
$tpl->CHECKED_RA13 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'14. LSML - refeição sem sal') !== false)
$tpl->CHECKED_RA14 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'15. MOML - refeição muçulmana') !== false)
$tpl->CHECKED_RA15 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'16. NLML - refeição sem lactose') !== false)
$tpl->CHECKED_RA16 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'17. ORML - refeição oriental  ( confeccionada com padroes orientais)') !== false)
$tpl->CHECKED_RA17 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'18. PRML - refeição com baixo teor de componentes que provoquem ácido úrico)') !== false)
$tpl->CHECKED_RA18 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'19. SFML - refeição de peixe (trata-se de peixe, não marisco )') !== false)
$tpl->CHECKED_RA19 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'20. VLML - refeição vegetariana ocidental ( com produtos lácteos e ovo)') !== false)
$tpl->CHECKED_RA20 = ' checked';
if(strpos($oCliente->restricaoAlimentar,'21. VGML - refeição vegetariana pura (sem produtos animais , lácteos e ovo)') !== false)
$tpl->CHECKED_RA21 = ' checked';

$tpl->PAIS_NASCIMENTO = $oCliente->paisNascimento;
$tpl->ESTADO_NASCIMENTO = $oCliente->estadoNascimento;
$tpl->CIDADE_NASCIMENTO = $oCliente->cidadeNascimento;
$tpl->PAIS_ENDERECO = $oCliente->paisEndereco;
$tpl->ESTADO_ENDERECO = $oCliente->estadoEndereco;
$tpl->CIDADE_ENDERECO = $oCliente->cidadeEndereco;

if($oCliente->restricaoAlimentar != ""){
$tpl->DISPLAY_RA = '';
$tpl->SELECTED_SIM_RA = ' selected ';
$tpl->SELECTED_NAO_RA = '';
}


if($oCliente->sexo == "M")
$tpl->CHECKED_SEXOM = 'selected';
else
$tpl->CHECKED_SEXOF = 'selected';

if($oCliente->preferencial == 0)
$tpl->CHECKED_PREFN = 'selected';
else
$tpl->CHECKED_PREFS = 'selected';


$tpl->ACAO = "Alterar";
$tpl->ID = $oCliente->id;
$contG = 0;
$listaGrupos = "";
$rsGrupo = $oGrupo->gruposDeCliente($oCliente->id);
foreach($rsGrupo as $keyG => $grupo){
$listaGrupos .= ($contG+1)."-".$grupo->nomePacote."<br/>";
$contG++;
}
$tpl->QTD_GRUPO = $contG;
$tpl->GRUPOS = $listaGrupos;
$tpl->block("BLOCK_GRUPOS");
}

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

$tpl->CIDADES = $oCliente->listaCidades();
$tpl->PAISES = $oCliente->listaPaises();
$tpl->ESTADOS = $oCliente->listaEstados();

include("tupi.template.finalizar.php"); 
?>