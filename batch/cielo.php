<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

include("../tupi.inicializar.php");
try{

    // Create some handlers
    $stream = new StreamHandler(__DIR__.'/log_cielo.log', Logger::DEBUG);
    $logger = new Logger('cielo');
    $logger->pushHandler($stream);
    @$logger->DEBUG($_REQUEST['MerchantOrderNumber']);
    
    @$logger->DEBUG($_REQUEST['URL']);
    @$logger->DEBUG($_REQUEST['MerchantId']);
    $obj = new MyCieloCheckout();
    $url  = isset($_REQUEST['URL']) ? $_REQUEST['URL'] : '';
    $idVenda = isset($_REQUEST['MerchantOrderNumber']) ?$_REQUEST['MerchantOrderNumber'] : '0s';
    $return = $obj->UpdateByNotification($url,$idVenda,$logger);
    @$logger->info($return);
    
    echo json_encode(array("code"=>"200","data"=>array("message"=>utf8_encode($return))));
}catch (Exception $e){    
    $mensagem = utf8_encode($e->getMessage());
    $logger->debug($mensagem);
echo json_encode(array("code"=>"500","data"=>array("mensagem"=>"$mensagem")));
}