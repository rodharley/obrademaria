<?php 
header("Content-Type: text/html; charset=iso-8859-1");
include("../admin/tupi.inicializar.php"); 
try{
$tupi->mail_html($tupi->REMETENTE,$_REQUEST['email'], 'Email do Site', 'Grupo: '.$_REQUEST['grupo'].'<br/>'.'Nome: '.$_REQUEST['nome'].'<br/>'.'Mensagem: '.$_REQUEST['message'].'<br/>');
echo json_encode(array("code"=>"200","data"=>array("mensagem"=>"Email Enviado com sucesso!")));
}catch(Exception $e){
$mensagem = utf8_encode($e->getMessage());
echo json_encode(array("code"=>"500","data"=>array("mensagem"=>"$mensagem")));
}
