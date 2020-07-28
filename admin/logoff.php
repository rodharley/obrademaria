<?php 
session_start();
unset($_SESSION['ag_nomeUsuario']);
unset($_SESSION['ag_idUsuario']);
unset($_SESSION['ag_perfilUsuario']);
unset($_SESSION['ag_idPerfilUsuario']);
unset($_SESSION['ag_emailUsuario']);
unset($_SESSION['ag_itensMenu']);

header("Location:index.php");
exit();
?>