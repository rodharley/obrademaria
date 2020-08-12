<?php
include("tupi.inicializar.php");
include("tupi.template.inicializar.php");
$codAcesso = 53;

include("tupi.seguranca.php");

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
$aba = isset($_REQUEST['aba']) ? $_REQUEST['aba'] : 0;
$obGaleria = new Galeria();
$obGrupo = new Grupo();
$tpl->PUBNAO = 'checked="true"';
$tpl->ACITVE_0 = $aba == 0 ? 'active' : '';
$tpl->ACITVE_1 = $aba == 1 ? 'active' : '';

if($id != 0){
if(!$obGaleria->getById($id)){
    //$obGaleria->getById(1);
    echo "galeria nao encontrada";
    exit();
}
$tpl->ID = $obGaleria->id;
$tpl->NAME = $obGaleria->name;
$tpl->PUBSIM =$obGaleria->publish == 1 ? 'checked="true"' : '';
$tpl->PUBNAO =$obGaleria->publish == 0 ? 'checked="true"' : '';


foreach($obGaleria->photos as $key => $value){
    $tpl->FOTO = $value->name;
    $tpl->ID_FOTO = $value->id;
    $tpl->DESCRIPTION_FOTO = $value->description;
    $tpl->block("BLOCK_FOTO");
}
$tpl->block("BLOCK_EDITAR");
$tpl->block("BLOCK_EDITAR_PILL");
}
$rsGrupos = $obGrupo->getRows();
$rsGalerias = $obGaleria->getRows();
foreach ($rsGalerias as $key => $value) {
    $tpl->ID_GALERIA = $value->id;
    $tpl->NOME_GALERIA = $value->name;
    if($id != 0)
        $tpl->SELECTED_GALERIA = $value->id == $id ? 'selected' : '';
    $tpl->block('BLOCK_GALERIA');
}
foreach ($rsGrupos as $key => $value) {
    $tpl->ID_GRUPO = $value->id;
    $tpl->NOME_GRUPO = $value->id."-".$value->nomePacote;
    if($id != 0)
        $tpl->SELECTED_GRUPO = $value->id == $obGaleria->grupo->id ? 'selected' : '';
    $tpl->block('BLOCK_GRUPO');
}



include("tupi.template.finalizar.php"); 