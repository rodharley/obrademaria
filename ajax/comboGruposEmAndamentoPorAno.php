<?php 
header("Content-Type: text/html; charset=iso-8859-1"); ?>
<?
include("../tupi.inicializar.php"); 
$oGrupo = new Grupo();
$arrayfiltro  = array("ano"=>" = '".$_REQUEST['Ano']."'","status"=>"=".$oGrupo->STATUS_ANDAMENTO());
$arrayorderm  = array("id"=>"ASC");
$grupos = $oGrupo->getRows(0,999,$arrayorderm,$arrayfiltro);
echo '<option value="" selected="selected">Selecione</option>';
foreach($grupos as $key => $grupo){
    echo '<option value="'.$oGrupo->md5_encrypt($grupo->id).';0">'.$grupo->nomePacote."-Sem Opcional".'</option>';
    if($grupo->possuiPacoteOpcional == 1){
    echo '<option value="'.$oGrupo->md5_encrypt($grupo->id).';1">'.$grupo->nomePacote."-Com Opcional".'</option>';
    }
}
?>