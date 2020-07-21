<?php
header("Content-Type: text/html; charset=iso-8859-1");
include("../../tupi.inicializar.php");
$obCliente = new Cliente();
try{
if($obCliente->getByCpf($obCliente->limpaDigitos($_REQUEST['cpf']))){
    $obCliente->URI = '';
    $obCliente->PASSWORDTXT = '';
    $obCliente->REMETENTE = '';
    $obCliente->TITULO = '';
    $obCliente->endpointcn = '';
    $obCliente->usercn = '';
    $obCliente->senhacn = '';
    $obCliente->clientIdGN = '';
    $obCliente->clientSecretGN = '';
    $obCliente->urlScripts = '';
    $obCliente->dataNascimento = $obCliente->convdata($obCliente->dataNascimento,"mtn");
    echo json_encode(array("code"=>"200","data"=>$obCliente->objectToArray($obCliente)));
}else{
    echo json_encode(array("code"=>"404","data"=>""));
}

}catch(Exception $e){
    $mensagem = utf8_encode($e->getMessage());
echo json_encode(array("code"=>"404","data"=>array("mensagem"=>"$mensagem")));
}