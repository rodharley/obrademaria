<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

include("../tupi.inicializar.php");
try{

    // Create some handlers
    $stream = new StreamHandler(__DIR__.'/log_cielo.log', Logger::DEBUG);
    $logger = new Logger('cielo');
    $logger->pushHandler($stream);
    $logger->info(print_r($_REQUEST));
    $obj = new MyCieloCheckout();
    $url  = $_REQUEST['URL'];
    $idVenda = $_REQUEST['MerchantOrderNumber'];
    $return = $obj->UpdateByNotification($url,$idVenda);
    $logger->info(print_r($return));
    echo json_encode(array("code"=>"200","data"=>array("message"=>$return)));
}catch (Exception $e){
    $logger->info(print_r($e));
    $mensagem = utf8_encode($e->getMessage());
echo json_encode(array("code"=>"500","data"=>array("mensagem"=>"$mensagem")));
}