<?php 
header("Content-Type: text/html; charset=iso-8859-1"); ?>
<?
include("../tupi.inicializar.php"); 
$oCidade = new Cidade();
$arrayfiltro  = array("siglaUf"=>" = '".$_REQUEST['idUf']."'");
$arrayorderm  = array("nome"=>"ASC");
$cidades = $oCidade->getRows(0,999,$arrayorderm,$arrayfiltro);

echo '<option value="" selected="selected">Selecione...</option>';
foreach($cidades as $key => $cidade){
echo '<option value="'.$cidade->id.'">'.$cidade->nome.'</option>';	
}
?>