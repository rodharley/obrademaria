<?php
if(!isset($_SESSION['ag_idUsuario'])){
header("Location:index.php");
exit();	
}
if(strpos($_SESSION['ag_itensMenu'],"$codAcesso") === false) {
$_SESSION['tupi.mensagem'] = 5;
header("Location:index.php");
exit();	
}?>