<?
include("../tupi.inicializar.php");
include("../tupi.template.inicializar.php"); 
$oSC = new StatusCheque();
$oC = new Cheque();
$oC->getById($_REQUEST['idCheque']);
$oSC->getById($_REQUEST['status']);
$oC->status = $oSC;
$oC->save();
$tpl->STATUS = $oSC->descricao;
$tpl->IDCHEQUE = $oC->id;
$rsstatus = $oSC->getRows();
foreach ($rsstatus as $key => $status){
$tpl->ID_STATUS = $status->id;
$tpl->LABEL_STATUS = $status->descricao;
$tpl->block("BLOCK_STATUS");	
}

include("../tupi.template.finalizar.php"); 
?>