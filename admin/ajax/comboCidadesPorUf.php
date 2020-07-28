<?php 
header("Content-Type: text/html; charset=iso-8859-1"); ?>
<?
include("../tupi.inicializar.php"); 
$oCidade = new Cidade();
$arrayfiltro  = array("siglaUf"=>" = '".$_REQUEST['idUf']."'");
$arrayorderm  = array("nome"=>"ASC");
$cidades = $oCidade->getRows(0,999,$arrayorderm,$arrayfiltro);

echo '<option value="0" selected="selected">Selecione...</option>';
foreach($cidades as $key => $cidade){
    if(isset($_REQUEST['selected'])){
        if($_REQUEST['selected'] == $cidade->nome){
            echo '<option value="'.$cidade->id.'" selected >'.$cidade->nome.'</option>';	
        }else{
        echo '<option value="'.$cidade->id.'" >'.$cidade->nome.'</option>';	
        }
    }else{
        echo '<option value="'.$cidade->id.'">'.$cidade->nome.'</option>';	
    }
}
?>
