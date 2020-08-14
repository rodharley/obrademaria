<?php
include("tupi.inicializar.php");
include("tupi.template.inicializar.php");
$codAcesso = 54;

include("tupi.seguranca.php");

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;

$obReview = new Review();


if($id != 0){
if(!$obReview->getById($id)){
    //$obGaleria->getById(1);
    echo "review nao encontrado";
    exit();
}
$tpl->ID = $obReview->id;
$tpl->NAME = $obReview->name;
$tpl->LOCAL = $obReview->local;
$tpl->FOTO_NAME = $obReview->photo;
$tpl->REVIEW = $obReview->review;
$tpl->DATA = substr($obReview->date,8,2)."/".substr($obReview->date,5,2)."/".substr($obReview->date,0,4);
}else{
    $tpl->REQUIRED_FOTO = "required";
}
$rscoments = $obReview->getCommentsWithOutRoteiro();
foreach ($rscoments as $key => $value) {
    $tpl->ID_REVIEW = $value->id;
    $tpl->FOTO_REVIEW = $value->photo;
    $tpl->COMENT_NAME = $value->name;
    $tpl->COMENT_LOCAL = $value->local;
    $tpl->COMENT_DATE = $obReview->convdata($value->date,"mtnh");
    $tpl->COMENT_REVIEW = $value->review;
    
    $tpl->block('BLOCK_REVIEW');
}



include("tupi.template.finalizar.php"); 