<?php
include("../tupi.inicializar.php");
try{
    //executa agendamentos
	$ag = new Agendamento();
	if(!$ag->debug){
			$ag->enviarEmailsAniversariantes();
			$ag->enviarEmailsCartoesPrePagos();
			$ag->enviarEmailsContasAPagar();
			$ag->enviarEmailsPassaportes();
			$ag->enviarEmailsChegadaGrupo();
    }		
    $ag->atualizaDollar();
}catch (Exception $e){
    echo "erro";
}