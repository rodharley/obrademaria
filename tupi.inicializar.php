<?php
//iniciando acess�o
session_start();

//incluindo todas as classes
require("class/ready.php");
$tupi = new Tupi();
$tupi->abrirConexao();

?>