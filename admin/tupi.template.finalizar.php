<?php
if(isset($_REQUEST['tupiSendEmail'])){
$msg = $tpl->showString();
//carregando o arquivo css
// get contents of a file into a string
$filename = "css/bootstrap.css";
$handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
fclose($handle);

$CssConverte = new CSSToInlineStyles($msg,$contents);
$html = $CssConverte->convert();
$result = $tupi->mail_html($_REQUEST['tupiSendEmail'],$tupi->REMETENTE, $tupi->TITULO, $html);
$oMensagem = new Mensagem();
if($result){
$oMensagem->getMensagem(53);
}else{
$oMensagem->getMensagem(54);
}
$tipo = "";
if($oMensagem->tipo != "")
	$tipo = 'alert-'.$oMensagem->tipo;
$tpl->ALERT = '<div class="alert '.$tipo.'"><a class="close" data-dismiss="alert">x</a>'.utf8_decode($oMensagem->mensagem).'</div>';
$tpl->block("BLOCK_ENVIO_EMAIL");
$tpl->show();
}else{
$tpl->show();
}
?>