<?php
include("../tupi.inicializar.php");
$rs = $tupi->DAO_ExecutarQuery('SELECT email,nomeCompleto, dataNascimento FROM `ag_cliente` where month(dataNascimento) = month(now()) and day(dataNascimento) = day(now()) and email != ""');
$log = "Email de Aniversário mandado para: <br/>";
while($linha = $tupi->DAO_GerarArray($rs)){

$email = '<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td background="http://www.i9turismo.tur.br/admin/docs/cartao1.jpg" height="400" align="center"><font color="#FFFFFF" size="+2" face="Palatino Linotype, Book Antiqua, Palatino, serif">'.$linha['nomeCompleto'].'</font></td>
  </tr>
</table>';

if($tupi->mail_html($linha['email'],$tupi->REMETENTE, 'FELIZ ANIVERSÁRIO!', $email))
	$log .= $linha['nomeCompleto']." Enviado<br/>";
else
	$log .= $linha['nomeCompleto']." Falha no envio<br/>";
}
$tupi->mail_html($tupi->REMETENTE,$tupi->REMETENTE, 'Agendamento', $log);
echo "Rodou 3.0 ".$log;
?>