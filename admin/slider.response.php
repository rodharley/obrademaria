<?php
include("tupi.inicializar.php");
$codAcesso = 52;
include("tupi.seguranca.php");

$id =$_REQUEST['id'];

$obSlider = new Slide();

if($id != ''){
$obSlider->getById($id);

}


switch($_REQUEST['acao']){
    case 'excluir':
        $obSlider->excluir();  
        $_SESSION['tupi.mensagem'] = 67;
        header('Location:slider.php');
        exit();         
    break;
    
    case 'save':
        $obSlider->roteiro = new Roteiro($_REQUEST['roteiro']);
         $obSlider->title = $_REQUEST['title'];
         $obSlider->subTitle = $_REQUEST['subtitle'];
         $obSlider->description = $_REQUEST['description'];
        $obSlider->buttomText = $_REQUEST['textButtom'];
        $obSlider->publish = $_REQUEST['publish'];
        $obSlider->salvaImage($_FILES['image']);
        $obSlider->save();
    break;
   
}
$_SESSION['tupi.mensagem'] = 67;
header('Location:slider.php?id='.$obSlider->id);
exit();
