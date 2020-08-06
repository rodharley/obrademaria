<?php
include("tupi.inicializar.php");
include("tupi.template.inicializar.php");
$codAcesso = 52;

include("tupi.seguranca.php");

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;


$obSlider = new Slide();
$obRoteiro = new Roteiro();
$obGrupo = new Grupo();
$obFoto = new Foto();
$obVideo = new Video();
$obEtinerario = new Etinerario();
$tpl->PUBNAO = 'checked="true"';

if($id != 0){
if(!$obSlider->getById($id)){
    //$obRoteiro->getById(1);
    echo "slide nao encontrado";
    exit();
}
$tpl->ID = $obSlider->id;
$tpl->TITLE = $obSlider->title;
$tpl->SUB_TITLE = $obSlider->subTitle;
$tpl->DESCRIPTION = $obSlider->description;
$tpl->TEXT_BUTTOM = $obSlider->buttomText;
$tpl->IMAGE = $obSlider->image;
$tpl->PUBSIM = $obSlider->publish == 1 ? 'checked="true"' : '' ;
$tpl->PUBNAO = $obSlider->publish == 0 ? 'checked="true"' : '' ;
}
$rsRoteiros = $obRoteiro->getRoteirosSemSlider();
if($id != 0){
    array_push($rsRoteiros,$obSlider->roteiro);
}
$rsSliders = $obSlider->getRows();

foreach ($rsSliders as $key => $value) {
    $tpl->ID_SLIDER = $value->id;
    $tpl->TITLE_SLIDER = $value->title;
    if($id != 0)
        $tpl->SELECTED_SLIDER = $value->id == $obSlider->id ? 'selected' : '';
    $tpl->block('BLOCK_SLIDER');
}
foreach ($rsRoteiros as $key => $value) {
    $tpl->ID_ROTEIRO = $value->id;
    $tpl->TITLE_ROTEIRO = $value->cardTitle;
    if($id != 0)
        $tpl->SELECTED_ROTEIRO = $value->id == $obSlider->roteiro->id ? 'selected' : '';
    $tpl->block('BLOCK_ROTEIRO');
}



include("tupi.template.finalizar.php"); 