<?php
include("tupi.inicializar.php");
$codAcesso = 1;
include("tupi.seguranca.php");

$id =$_REQUEST['id'];
$obRoteiro = new Roteiro();
$obRoteiro->getById($id);
$obGrupo = new Grupo();
$obGrupo->getById($obRoteiro->grupo->id);
switch($_REQUEST['acao']){
    case 'dadosGerais':
        $obGrupo->local = $_REQUEST['local'];
        $obGrupo->idadeMinima = $_REQUEST['idadeMinima'];
        $obGrupo->maxPessoa = $_REQUEST['maxPessoa'];
        $obGrupo->duracao = $_REQUEST['duracao'];
        $obGrupo->save();
        $obRoteiro->setCountDown($_REQUEST['countdown']);
        $obRoteiro->continent = implode(" E ",$_REQUEST['continent']);
        $obRoteiro->likes = $_REQUEST['likes'];
        $obRoteiro->unlikes = $_REQUEST['unlikes'];
        $obRoteiro->save();

    break;
    case 'dadosCard':
         $obRoteiro->cardTitle = $_REQUEST['cardTitle'];
        $obRoteiro->cardDescription = $_REQUEST['cardDescription'];
        $obRoteiro->salvaCardImage($_FILES['cardImage']);
        $obRoteiro->save();

    break;
    case 'dados':
        $obRoteiro->title = $_REQUEST['title'];
       $obRoteiro->description = $_REQUEST['description'];
       $obRoteiro->salvaImage($_FILES['image']);
       $obRoteiro->save();

   break;
}
$_SESSION['tupi.mensagem'] = 67;
header('Location:roteiro.php?id='.$obRoteiro->id);
exit();
