<?php
include("tupi.inicializar.php");
include("tupi.template.inicializar.php");
$codAcesso = 1;

include("tupi.seguranca.php");

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 1;


$obRoteiro = new Roteiro();
$obRoteiro->getById($id);
$tpl->ID = $obRoteiro->id;
$tpl->DURACAO = $obRoteiro->grupo->duracao;
$tpl->MAX_PESSOA = $obRoteiro->grupo->maxPessoa;
$tpl->IDADE_MINIMA = $obRoteiro->grupo->idadeMinima;
$tpl->LOCAL = $obRoteiro->grupo->local;
$tpl->UNLIKES = $obRoteiro->unlikes;
$tpl->LIKES = $obRoteiro->likes;
$tpl->RADIOSIM =$obRoteiro->countDown == 1 ? 'checked="true"' : '';
$tpl->RADIONAO =$obRoteiro->countDown == 0 ? 'checked="true"' : '';
$tpl->CHECKED_OCE = strpos($obRoteiro->continent,"OCEANIA") !== false ? 'checked="true"' : '';
$tpl->CHECKED_EUR = strpos($obRoteiro->continent,"EUROPA") !== false ? 'checked="true"' : '';
$tpl->CHECKED_ASIA = strpos($obRoteiro->continent,"ÁSIA") !== false ? 'checked="true"' : '';
$tpl->CHECKED_AMS = strpos($obRoteiro->continent,"AMÉRICA DO SUL") !== false ? 'checked="true"' : '';
$tpl->CHECKED_AMN = strpos($obRoteiro->continent,"AMÉRICA DO NORTE") !== false ? 'checked="true"' : '';
$tpl->CHECKED_AFRICA = strpos($obRoteiro->continent,"ÁFRICA") !== false ? 'checked="true"' : '';
$tpl->CARD_IMAGE = $obRoteiro->cardImage;
$tpl->CARD_DESCRIPTION = $obRoteiro->cardDescription;
$tpl->CARD_TITLE = $obRoteiro->cardTitle;
$tpl->IMAGE = $obRoteiro->image;
$tpl->DESCRIPTION = $obRoteiro->description;
$tpl->TITLE = $obRoteiro->title;
$rsRoteiros = $obRoteiro->getRows();

foreach ($rsRoteiros as $key => $value) {
    $tpl->ID_ROTEIRO = $value->id;
    $tpl->NOME_ROTEIRO = $value->cardTitle;
    $tpl->SELECTED_ROTEIRO = $value->id == $obRoteiro->id ? 'selected' : '';
    $tpl->block('BLOCK_ROTEIRO');
}



include("tupi.template.finalizar.php"); 