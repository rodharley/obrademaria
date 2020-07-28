<?php
//echo 'Em processo de migra��o...';
//exit();
include("tupi.inicializar.php");
include("tupi.template.inicializar.php");

if(isset($_COOKIE['loginODM']))
$tpl->COOKIE_LOGIN = $_COOKIE['loginODM'];
include("tupi.template.finalizar.php");
?>