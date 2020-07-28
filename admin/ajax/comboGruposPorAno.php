<?php 
header("Content-Type: text/html; charset=iso-8859-1"); ?>
<?
include("../tupi.inicializar.php"); 
$oGrupo = new Grupo();
$arrayfiltro  = array("ano"=>" = '".$_REQUEST['Ano']."'");
$arrayorderm  = array("id"=>"ASC");
$grupos = $oGrupo->getRows(0,999,$arrayorderm,$arrayfiltro);
echo '<option value="" selected="selected">Selecione</option>';
foreach($grupos as $key => $grupo){
echo '<option value="'.$oGrupo->md5_encrypt($grupo->id).'">'.$grupo->nomePacote.'</option>';	
}
?>