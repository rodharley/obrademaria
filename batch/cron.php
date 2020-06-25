<?php
include("../tupi.inicializar.php");
try{
    $ag = new Agendamento();
    $ag->atualizaDollar();
}catch (Exception $e){
    echo "erro";
}