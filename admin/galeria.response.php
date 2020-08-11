<?php
include("tupi.inicializar.php");
$codAcesso = 53;
include("tupi.seguranca.php");

$id =$_REQUEST['id'];
$obGaleria = new Galeria();
$obGrupo = new Grupo();


$aba="0";
if($id != ''){
$obGaleria->getById($id);
}


switch($_REQUEST['acao']){
    case 'excluir':
        $obGaleria->excluir();     
        $_SESSION['tupi.mensagem'] = 67;
        header('Location:galeria.php');
        exit();   
    break;
    case 'dadosGerais':
        $obGrupo->id = $_REQUEST['grupo'];                
        $obGaleria->grupo = $obGrupo;
        $obGaleria->publish = $_REQUEST['publish'];
        $obGaleria->name = $_REQUEST['name'];
        $obGaleria->save();
        $aba= "0";   
    break;
    
   case 'foto':
    $obGaleria->salvaFoto($_FILES['foto']); 
    $aba= "1";   
    break;
    case 'excluirfoto':
        $obGaleria->excluirFoto($_REQUEST['idfoto']);
        $aba= "1";
    break;
}
$_SESSION['tupi.mensagem'] = 67;
header('Location:galeria.php?aba='.$aba.'&id='.$obGaleria->id);
exit();
