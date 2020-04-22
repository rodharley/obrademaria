<?php
//iniciando acess�o
session_start();
date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.iso-8859-1', 'portuguese');
header('Content-Type: text/html; charset=iso-8859-1');

//incluindo todas as classes
include("class/ready.php");

include("vendor/autoload.php");
$tupi = new Tupi();
?>