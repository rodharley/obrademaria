<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 5;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '<ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="#">Cadastro</a> <span class="divider">/</span>
    </li>
    <li class="active">Lista Clientes</li>
    </ul>';
}
$oCliente = new Cliente();
$oGrupo = new Grupo();
$rsAnos = $oGrupo->recuperaAnos();
$strBusca = isset($_REQUEST['busca']) ? str_replace(".","",str_replace("-","",$_REQUEST['busca'])) : "";
$totalClientes = $oCliente->recuperaTotal($strBusca);
$pagina = isset($_REQUEST['pagina']) ? $_REQUEST['pagina'] : 1;
$configPaginacao = $oCliente->paginar($totalClientes,$pagina);
$rsClientes = $oCliente->Pesquisa($configPaginacao['primeiroRegistro'],$configPaginacao['quantidadePorPagina'],$strBusca);	

if($configPaginacao['totalPaginas'] > 1){
$tpl->block("BLOCK_PAGINACAO");
}

$tpl->TOTAL_PAGINAS = $configPaginacao['totalPaginas'];
$tpl->PAGINA_ANTERIOR = $configPaginacao['paginaAnterior'];
$tpl->PROXIMA_PAGINA = $configPaginacao['proximaPagina'];
$tpl->PAGINA = $pagina;
$tpl->BUSCA = $strBusca;

foreach($rsClientes as $key => $Cliente){
	$tpl->ID = $Cliente->id;
	$tpl->NOME = $Cliente->nomeCompleto;
	$tpl->CPF = $oCliente->formataCPFCNPJ($Cliente->cpf);
	$tpl->ID_HASH = $oCliente->md5_encrypt($Cliente->id);
$tpl->block("BLOCK_ITEM_LISTA");	
}

while($row = $oGrupo->DAO_GerarArray($rsAnos)){
    $tpl->ID_ANO = $row['ano'];
    $tpl->LABEL_ANO = $row['ano'];  
    $tpl->block("BLOCK_ANO");   
}




include("tupi.template.finalizar.php"); 
?>