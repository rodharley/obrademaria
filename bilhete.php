<?php 

include("tupi.inicializar.php");
$codTemplate = 'tpl_shopping';
include("tupi.template.inicializar.php"); 
//$tupi->trataRequestAntiInjection();
unset($_SESSION['ag_nomeUsuario']);
unset($_SESSION['ag_idUsuario']);
unset($_SESSION['ag_perfilUsuario']);
unset($_SESSION['ag_idPerfilUsuario']);
unset($_SESSION['ag_emailUsuario']);
unset($_SESSION['ag_itensMenu']);

//CARREGA DADOS DO GRUPO 
if(isset($_REQUEST['charge_id'])){
    $oGn = new GerenciaNetCheckOut();
    if($oGn->getByChargeId($_REQUEST['charge_id'])){
    $tpl->URLGN = $oGn->payment_url;
    $tpl->CHARGE_ID = $oGn->charge_id;
    $tpl->block("COMPRA_VALIDA");
    }else{
        $tpl->block("COMPRA_INVALIDA");
    }
}
include("tupi.template.finalizar.php"); 