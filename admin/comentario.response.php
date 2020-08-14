<?php
include("tupi.inicializar.php");
$codAcesso = 54;
include("tupi.seguranca.php");

$id =$_REQUEST['id'];

$obReview = new Review();


if($id != ''){
$obReview->getById($id);
}


switch($_REQUEST['acao']){
    case 'excluir':
        $obReview->excluir();     
    break;
    case 'salvar':
         $obReview->salvaReviewWithOutRoteiro($_FILES['foto'],$_REQUEST['name'],$_REQUEST['data'],$_REQUEST['coment'],$_REQUEST['local']);
    break;
    
}
$_SESSION['tupi.mensagem'] = 67;
header('Location:comentario.php');
exit();
