<?php 
header("Content-Type: text/html; charset=iso-8859-1");
include("../admin/tupi.inicializar.php"); 
try{
    if(isset($_SESSION['userIplike'])){
        if($_SERVER['REMOTE_ADDR'] == $_SESSION['userIplike']){
            throw new Exception("Você já votou");
        }
    }
    
    $obRoteiro = new Roteiro();
    if($obRoteiro->getById($_POST['roteiro'])){
        if($_POST['like'] == 1){
            $obRoteiro->likes++;
        }else{
            $obRoteiro->unlikes++;
        }
        $obRoteiro->save();
        $_SESSION['userIplike'] = $_SERVER['REMOTE_ADDR'];
        echo json_encode(array("code"=>"200","data"=>array("stars"=>$obRoteiro->getNumberStars(),"likes"=>$obRoteiro->likes,"unlikes"=>$obRoteiro->unlikes)));
    }else{
        throw new Exception("Roteiro não encontrado");
    }
}catch(Exception $e){
$mensagem = utf8_encode($e->getMessage());
echo json_encode(array("code"=>"500","data"=>array("mensagem"=>"$mensagem")));
}
