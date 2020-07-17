<?php
include("../tupi.inicializar.php");
try{
    $obj = new MyCieloCheckout();
    $url  = $_REQUEST['URL'];
    $idVenda = $_REQUEST['MerchantOrderNumber'];
    $return = $obj->UpdateByNotification($url,$idVenda);
    echo json_encode(array("code"=>"200","data"=>array("message"=>$return)));
}catch (Exception $e){
    $mensagem = utf8_encode($e->getMessage());
echo json_encode(array("code"=>"500","data"=>array("mensagem"=>"$mensagem")));
}