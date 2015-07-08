<?php 
include("tupi.inicializar.php"); 
$codTemplate = $_REQUEST['etiqueta'];
include("tupi.template.inicializar.php"); 
$codAcesso = 25;
include("tupi.seguranca.php");
//titulo do relatorio
$tpl->TITULO = "Etiquetas Passaportes à Vencer";
$oC = new Cliente();
$rs = $oC->pesquisaPassaportesVencer();
$contEtiqueta = 0;
foreach($rs as $key => $cliente){
$maximoFolha = false;
$tpl->NOME = $cliente->nomeCompleto;
$tpl->ENDERECO = $cliente->endereco;
$tpl->BAIRRO = $cliente->bairro;
$tpl->CIDADE = $cliente->cidadeEndereco;
$tpl->UF = $cliente->estadoEndereco;
$tpl->PAIS = $cliente->paisEndereco;
$tpl->CEP = $cliente->cep;
$tpl->block("BLOCK_ETIQUETA");
$contEtiqueta ++;
if($contEtiqueta == 20){
$contEtiqueta = 0;
$maximoFolha = true;
$tpl->block("BLOCK_FOLHA");
}
}
if(!$maximoFolha)
$tpl->block("BLOCK_FOLHA");
include("tupi.template.finalizar.php"); 
?>