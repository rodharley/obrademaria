<?php
include("../tupi.inicializar.php");
try{
$obj = new GerenciaNetCheckOut();
$obj->UpdateByNotification();
}catch (Exception $e){
    echo "erro";
}