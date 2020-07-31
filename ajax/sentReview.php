<?php 
header("Content-Type: text/html; charset=iso-8859-1");
include("../admin/tupi.inicializar.php"); 
unset($_SESSION['userIpReview']);
try{
    if(isset($_SESSION['userIpReview'])){
        if($_SERVER['REMOTE_ADDR'] == $_SESSION['userIpReview']){
            throw new Exception("Você já enviou");
        }
    }
   
    $obReview = new Review();
    $obReview->name = $_POST['name'];
    $obReview->email = $_POST['email'];
    $obReview->review = $_POST['message'];
    $obReview->date = date("Y-m-d H:i:s");
    $obReview->roteiro = new Roteiro($_POST['roteiro']);
    $obReview->save();
    $_SESSION['userIpReview'] = $_SERVER['REMOTE_ADDR'];
    echo json_encode(array("code"=>"200","data"=>array("mensagem"=>utf8_encode("Comentário Salvo com sucesso!"))));
   exit();
}catch(Exception $e){
$mensagem = utf8_encode($e->getMessage());
echo json_encode(array("code"=>"500","data"=>array("mensagem"=>"$mensagem")));
}