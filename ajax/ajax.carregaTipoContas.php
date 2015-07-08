<?
include("../tupi.inicializar.php");
include("../tupi.template.inicializar.php"); 
$oTipoC = new TipoConta();
$oConta = new Conta();

if(isset($_REQUEST['idConta']) && strlen($_REQUEST['idConta']) > 0){
$oConta->getById($_REQUEST['idConta']);
}

switch($_REQUEST['idTipo']){
case $oTipoC->UNICA() :
	$tpl->PARCELA = "1";
	$tpl->block('BLOCK_TIPO_UNICA');
break;
case $oTipoC->PERIODICA() :
	$tpl->PARCELA = "0";
	$tpl->block('BLOCK_TIPO_PERIODICA');
break;
case $oTipoC->PERIODO() :
	$tpl->PARCELA = "2";
	$tpl->block('BLOCK_TIPO_PERIODO');
	
break;
}
include("../tupi.template.finalizar.php"); 
?>