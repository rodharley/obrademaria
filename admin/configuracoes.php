<?php
include("tupi.inicializar.php");
include("tupi.template.inicializar.php");
$codAcesso = 55;

include("tupi.seguranca.php");
$obAg = new Agendamento();
$obAg->getById(7);
$tpl->URL_REVISTA = $obAg->destinatarios;
include("tupi.template.finalizar.php"); 