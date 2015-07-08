<?php 
header("Content-Type: text/html; charset=iso-8859-1"); ?>
<?
include("../tupi.inicializar.php"); 
$oParticipante = new Participante();
$arrayfiltro  = array("grupo"=>" = '".$oParticipante->md5_decrypt($_REQUEST['idGrupo'])."'");
$arrayorderm  = array("id"=>"ASC");
$participantes = $oParticipante->getRows(0,999,$arrayorderm,$arrayfiltro);
echo '<option value="" selected="selected">Selecione</option>';
foreach($participantes as $key => $partic){
echo '<option value="'.$partic->id.'">'.$partic->cliente->nomeCompleto.'</option>';	
}
?>