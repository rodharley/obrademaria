<?php
header("Content-Type: text/html; charset=iso-8859-1");
include("../tupi.inicializar.php");
//carregando o arquivo css
// get contents of a file into a string
$filename = "../css/bootstrap.css";
$handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
fclose($handle);
$msg = utf8_decode($_REQUEST['textoEmailRelat']);
$CssConverte = new CSSToInlineStyles($msg,$contents);
$html = $_REQUEST['infoEmailRelat']."<br><br>".$CssConverte->convert();
$result = $tupi->mail_html($_REQUEST['destinatario'],$tupi->REMETENTE, $tupi->TITULO, $html);
$oMensagem = new Mensagem();
if($result){
$oMensagem->getMensagem(53);
}else{
$oMensagem->getMensagem(54);
}
echo utf8_decode($oMensagem->mensagem);


?>