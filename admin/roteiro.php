<?php
include("tupi.inicializar.php");
include("tupi.template.inicializar.php");
$codAcesso = 1;

include("tupi.seguranca.php");

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;


$obRoteiro = new Roteiro();
$obGrupo = new Grupo();
$obFoto = new Foto();
$obVideo = new Video();
$tpl->RADIONAO = 'checked="true"';
if($id != 0){
if(!$obRoteiro->getById($id)){
    //$obRoteiro->getById(1);
    echo "roteiro nao encontrado";
    exit();
}
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

$rsfotos = $obFoto->getByRoteiro($obRoteiro->id);
foreach($rsfotos as $key => $value){
    $tpl->FOTO = $value->name;
    $tpl->ID_FOTO = $value->id;
    $tpl->block("BLOCK_FOTO");
}
$rsVideos = $obVideo->getByRoteiro($obRoteiro->id);
if(count($rsVideos)>0){
$tpl->VIDEO = $rsVideos[0]->name;
}
$tpl->block("BLOCK_EDITAR");
$tpl->block("BLOCK_EDITAR_PILL");
}
$rsRoteiros = $obRoteiro->getRows();
$rsGrupos = $obGrupo->getRows(0,999,array("id"=>"desc"),array("status"=>"=1"));
foreach ($rsRoteiros as $key => $value) {
    $tpl->ID_ROTEIRO = $value->id;
    $tpl->NOME_ROTEIRO = $value->cardTitle;
    if($id != 0)
        $tpl->SELECTED_ROTEIRO = $value->id == $obRoteiro->id ? 'selected' : '';
    $tpl->block('BLOCK_ROTEIRO');
}
foreach ($rsGrupos as $key => $value) {
    $tpl->ID_GRUPO = $value->id;
    $tpl->NOME_GRUPO = $value->id."-".$value->nomePacote;
    if($id != 0)
        $tpl->SELECTED_GRUPO = $value->id == $obRoteiro->grupo->id ? 'selected' : '';
    $tpl->block('BLOCK_GRUPO');
}



include("tupi.template.finalizar.php"); 