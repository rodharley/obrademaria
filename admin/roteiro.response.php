<?php
include("tupi.inicializar.php");
$codAcesso = 51;
include("tupi.seguranca.php");

$id =$_REQUEST['id'];
$obRoteiro = new Roteiro();
$obGrupo = new Grupo();
$obFoto = new Foto();
$obVideo = new Video();
$obEtinerario = new Etinerario();

$aba="0";
if($id != ''){
$obRoteiro->getById($id);
}


switch($_REQUEST['acao']){
    case 'excluir':
        $obRoteiro->excluir();     
        $_SESSION['tupi.mensagem'] = 67;
        header('Location:roteiro.php');
        exit();   
    break;
    case 'dadosGerais':
        $obGrupo->getById($_REQUEST['grupo']);
        $obGrupo->local = $_REQUEST['local'];
        $obGrupo->idadeMinima = $_REQUEST['idadeMinima'];
        $obGrupo->maxPessoa = $_REQUEST['maxPessoa'];
        $obGrupo->duracao = $_REQUEST['duracao'];
        $obGrupo->save();
        
        $obRoteiro->grupo = $obGrupo;
        $obRoteiro->publish = $_REQUEST['publish'];
        $obRoteiro->setCountDown($_REQUEST['countdown']);
        $obRoteiro->continent = implode(" E ",$_REQUEST['continent']);
        $obRoteiro->likes = $_REQUEST['likes'];
        $obRoteiro->unlikes = $_REQUEST['unlikes'];
        if($obRoteiro->cardTitle == ''){
            $obRoteiro->cardTitle = $obGrupo->nomePacote;
        }
        $obRoteiro->save();

    break;
    case 'dadosCard':
         $obRoteiro->cardTitle = $_REQUEST['cardTitle'];
        $obRoteiro->cardDescription = $_REQUEST['cardDescription'];
        $obRoteiro->salvaCardImage($_FILES['cardImage']);
        $obRoteiro->save();
        $aba= "1";

    break;
    case 'dados':
        $obRoteiro->title = $_REQUEST['title'];
       $obRoteiro->description = $_REQUEST['description'];
       $obRoteiro->salvaImage($_FILES['image']);
       if($_REQUEST['video'] != ''){
           $rsvideos = $obVideo->getByRoteiro($obRoteiro->id);
           if(count($rsvideos)>0){
            $video = $rsvideos[0];
           }else{
               $video = new Video();
               $video->roteiro = $obRoteiro;
           }
           $video->name = $_REQUEST['video'];
           $video->save();

       }else{
        $rsvideos = $obVideo->getByRoteiro($obRoteiro->id);
        if(count($rsvideos) > 0){
            $video = $rsvideos[0];
            $video->delete($video->id);
        }
        }
       $obRoteiro->save();
       $aba= "2";
   break;
   case 'foto':
    $obFoto->salvaFoto($_FILES['foto'],$obRoteiro); 
    $aba= "3";   
    break;
    case 'excluirfoto':
        $obFoto->getById($_REQUEST['idfoto']);
        $obFoto->excluir();
        $aba= "3";
    break;
    case 'itinerary':
        $obEtinerario->salva($_REQUEST['order'],$_REQUEST['title'],$_REQUEST['description'],$obRoteiro);    
        $aba= "4";
        break;
        case 'ecluirItinerary':            
            $obEtinerario->excluir($_REQUEST['iditineray']);
            $aba= "4";
        break;
}
$_SESSION['tupi.mensagem'] = 67;
header('Location:roteiro.php?aba='.$aba.'&id='.$obRoteiro->id);
exit();
