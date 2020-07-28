<?php
if(!isset($_SESSION['ag_idUsuario'])){
    echo json_encode(array("code"=>"403","data"=>array("mensagem"=>'Usuario não logado')));
exit();	
}
if(strpos($_SESSION['ag_itensMenu'],"$codAcesso") === false) {
    echo json_encode(array("code"=>"403","data"=>array("mensagem"=>'Permissão negada')));
exit();	
}?>