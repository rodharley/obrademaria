<?php
include("tupi.inicializar.php");
include("tupi.template.inicializar.php");
$codAcesso = 36;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '<ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="#">Relat�rios</a> <span class="divider">/</span>
    </li>
    <li class="active">Relat�rio Geral por Per�odo</li>
    </ul>';
}

include("tupi.template.finalizar.php");
?>