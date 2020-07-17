<?php

include("../tupi.inicializar.php");
            
$dia = date("Y-m-d");
$sql  ="SELECT * FROM ag_cliente where month(dataNascimento) = month('".$dia."') and day(dataNascimento) = day('".$dia."')";
echo "<CENTER><H2>ANIVERSSARIANTES DO DIA</H2></CENTER><BR/>";
$obCliente = new Cliente();
$rs = $obCliente->getSQL($sql);
echo    "NOME;EMAIL;CELULAR<br/>";
foreach ($rs as $key => $value) {
    # code...
    echo $value->nomeCompleto.";".$value->email.";".$value->celular."<br/>";
}
?>