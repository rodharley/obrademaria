<?php 
header("Content-Type: text/html; charset=iso-8859-1");
include("../admin/tupi.inicializar.php"); 
try{
    if(isset($_SESSION['userIpemail'])){
        if($_SERVER['REMOTE_ADDR'] == $_SESSION['userIpemail']){
            throw new Exception("Você já enviou");
        }
    }
if(isset($_REQUEST['grupo'])){
$mensagem = 'Grupo: '.$_REQUEST['grupo'].'<br/>'.'Nome: '.$_REQUEST['nome'].'<br/>'.'Mensagem: '.$_REQUEST['message'];
}else{
$mensagem = 'Mensagem: '.$_REQUEST['message'];    
}

if($tupi->mail_html("brasilia@obrademaria.com.br",$_REQUEST['email'], 'Email do Site', $mensagem)){
    $_SESSION['userIpemail'] = $_SERVER['REMOTE_ADDR'];
    echo json_encode(array("code"=>"200","data"=>array("mensagem"=>utf8_encode("Email Enviado com sucesso!"))));
}else{
    echo json_encode(array("code"=>"500","data"=>array("mensagem"=>utf8_encode("Não foi possível enviar o Email, tente novamente mais tarde"))));
}

}catch(Exception $e){
$mensagem = utf8_encode($e->getMessage());
echo json_encode(array("code"=>"500","data"=>array("mensagem"=>"$mensagem")));
}
