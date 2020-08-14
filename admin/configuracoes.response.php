<?php
include("tupi.inicializar.php");

$codAcesso = 55;

include("tupi.seguranca.php");
$obAg = new Agendamento();
$obAg->getById(7);
$obAg->destinatarios = $_REQUEST['urlRevista'];
$obAg->save();
$_SESSION['tupi.mensagem'] = 67;
header('Location:configuracoes.php');
