<?php

include("../tupi.inicializar.php");
            
$dia = date("Y-m-d");
$sql  ="SELECT * FROM ag_cliente where month(dataNascimento) = month('".$dia."') and day(dataNascimento) = day('".$dia."') and email != ''";
echo "<CENTER><H2>ANIVERSSARIANTES DO DIA</H2></CENTER><BR/>";
$obCliente = new Cliente();
$rs = $obCliente->getSQL($sql);
foreach ($rs as $key => $value) {
    # code...
echo    "NOME;EMAIL;CELULAR<br/>";
    echo $value->nomeCompleto.";".$value->email.";".$value->celular."<br/>";
}
?>