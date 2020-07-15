<?php
include("../tupi.inicializar.php");
try{
$codAcesso = 50;
include("tupi.seguranca.php");
$pag = new Pagamento();
$pag->getById($_REQUEST['id']);
$pag->pago = $pag->pago == 0 ? 1 : 0;
$pag->save();
$pag->participante->atualiza_status();
echo json_encode(array("code"=>"200","data"=>array("status"=>$pag->pago)));
}catch(Exception $e){
    echo json_encode(array("code"=>"500","data"=>array("mensagem"=>$e->getMessage())));
    exit();
}