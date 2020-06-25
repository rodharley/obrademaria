<?php
include("tupi.inicializar.php");
$valorunico = strval(10033);
echo $valorunico."<br/>";
$valor = doubleval(substr($valorunico,0,strlen($valorunico)-2).".".substr($valorunico,strlen($valorunico)-2))/4;
echo $valor;