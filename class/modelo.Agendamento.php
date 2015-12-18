<?php
class Agendamento extends Persistencia{
	var $id = NULL;
	var $data ;
	var $descricao;
	var $destinatarios ;

function enviarEmailTeste(){
	$this->getById(1);
	echo "Remetente: " . $this->REMETENTE . "<br/>";
	echo "Destinatários: " . $this->destinatarios . "<br/>";;
	if($this->mail_html("rodrigo.cruz76@gmail.com",$this->REMETENTE, 'Teste de envio de email', "Esse é um teste"))
	echo "enviado";
	else {
		echo "erro";
	}
}

 function enviarEmailsAniversariantes(){
 	$hoje = date("Y-m-d");
	$hojets = strtotime($hoje);
	$this->getById(1);
	$ultimoDiats = strtotime($this->data);
	$dia = date("Y-m-d",$ultimoDiats);
	$i = 1;
	$dia =  date("Y-m-d", mktime(0,0,0,date("m",$ultimoDiats),date("d",$ultimoDiats)+$i,date("Y",$ultimoDiats)));
	$i = 2;
	while(str_replace("-","",$dia) <= str_replace("-","",$hoje)){
	$sql  ='SELECT email,nomeCompleto, dataNascimento FROM `ag_cliente` where month(dataNascimento) = month("'.$dia.'") and day(dataNascimento) = day("'.$dia.'") and email != ""';
			$rs = $this->DAO_ExecutarQuery($sql);
			$log = "Email de Aniversário mandado para: <br/>";
			while($linha = $this->DAO_GerarArray($rs)){
			$email = '<table width="600" border="0" cellspacing="0" cellpadding="6" align="center">
			  <tr>
				<td background="http://www.obrademariadf.com.br/admin/docs/cartao1.jpg" height="336" align="right"  valign="top"><font color="#FFFFFF" size="+2" face="Palatino Linotype, Book Antiqua, Palatino, serif">'.$linha['nomeCompleto'].'</font></td>
			  </tr>
			</table>';

			if($this->mail_html($linha['email'],$this->REMETENTE, 'FELIZ ANIVERSÁRIO!', $email))
			$log .= $linha['nomeCompleto']." - Enviado<br/>";
			else
			$log .= $linha['nomeCompleto']." - Falha no envio<br/>";
			}
			$this->mail_html($this->destinatarios,$this->REMETENTE, 'Agendamento de Aniversariantes', $log);

	$dia =  date("Y-m-d", mktime(0,0,0,date("m",$ultimoDiats),date("d",$ultimoDiats)+$i,date("Y",$ultimoDiats)));
	$i++;
	}//fim do loop de dias
	$this->data = $hoje;
	$this->save();
 }




  function enviarEmailsCartoesPrePagos(){
 	$hoje = date("Y-m-d");
	$hojets = strtotime($hoje);
	$this->getById(2);
	$ultimoDiats = strtotime($this->data);
	$dia = date("Y-m-d",$ultimoDiats);
	$i = 1;
	$dia =  date("Y-m-d", mktime(0,0,0,date("m",$ultimoDiats),date("d",$ultimoDiats)+$i,date("Y",$ultimoDiats)));
	$i = 2;

	$oPag = new Pagamento();
	$log = "";
	while(str_replace("-","",$dia) <= str_replace("-","",$hoje)){
	$rs = $oPag->getRows(0,999,array(),array("dataPagamento"=> "='".$dia."'","tipo"=>"=2"));
	$log .= "Email de Cartões Pré-pagos para o dia: ".$this->convdata($dia,"mtn")." <br/>";
	$j = 0;
	foreach($rs as $key => $pagamento){
		$log .= "Grupo: ".$pagamento->participante->grupo->nomePacote." - Participante: ".$pagamento->participante->cliente->nomeCompleto." - Valor: ".$pagamento->valorPagamento." <br/>";
	$j++;
	}

	if($j > 0){
	$this->mail_html($this->destinatarios,$this->REMETENTE, 'Agendamento de Cartões de Crédito', $log);
	}
	$dia =  date("Y-m-d", mktime(0,0,0,date("m",$ultimoDiats),date("d",$ultimoDiats)+$i,date("Y",$ultimoDiats)));
	$i++;
	}//fim do loop de dias
	$this->data = $hoje;
	$this->save();
 }

  function enviarEmailsContasAPagar(){
 	$hoje = date("Y-m-d");
	$hojets = strtotime($hoje);
	$this->getById(3);
	$ultimoDiats = strtotime($this->data);
	$dia = date("Y-m-d",$ultimoDiats);
	$i = 1;
	$dia =  date("Y-m-d", mktime(0,0,0,date("m",$ultimoDiats),date("d",$ultimoDiats)+$i,date("Y",$ultimoDiats)));
	$i = 2;

	$oConta = new ContaRealizado();
	$log = "";
	while(str_replace("-","",$dia) <= str_replace("-","",$hoje)){
	$rs = $oConta->getRows(0,999,array(),array("dataPagamento"=> "='".$dia."'"));
	$log .= "Contas a pagar para o dia: ".$this->convdata($dia,"mtn")." <br/>";
	$j = 0;
	foreach($rs as $key => $conta){
		$log .= "Conta:".$conta->conta->descricao."<br/>Parcela:".$conta->parcela."<br/>Valor:".$oConta->money($conta->valorPagamento,"atb")."<br/><br/><br/>";
		$j++;
	}

	if($j > 0 ){
	$this->mail_html($this->destinatarios,$this->REMETENTE, 'Contas a Pagar', $log);
	}
	$dia =  date("Y-m-d", mktime(0,0,0,date("m",$ultimoDiats),date("d",$ultimoDiats)+$i,date("Y",$ultimoDiats)));
	$i++;
	}//fim do loop de dias
	$this->data = $hoje;
	$this->save();
 }


 function enviarEmailsPassaportes(){
 	$diaAtual = date("d");
	if($diaAtual == 1){
	$hoje = date("Y-m-d");
	$hojets = strtotime($hoje);
	$primeiroDia = date("Y-m-d", mktime(0,0,0,date("m",$hojets)+2,1,date("Y",$hojets)));
	$mests = strtotime($primeiroDia);
	$ultimoDia = date("Y-m-d", mktime(0,0,0,date("m",$hojets)+2,date("t",$mests),date("Y",$hojets)));
	$this->getById(4);

	if(str_replace("-","",$hoje) > str_replace("-","",$this->data)){

	$sql  ="SELECT email,nomeCompleto, dataValidadePassaporte FROM `ag_cliente` where dataValidadePassaporte between '$primeiroDia' and '$ultimoDia' and email != ''";


			$rs = $this->DAO_ExecutarQuery($sql);
			$log = "Email de Passaportes a vencer: <br/>";
			while($linha = $this->DAO_GerarArray($rs)){
			$email = '<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
			  <tr>
				<td height="400" align="left"><font color="#0000" size="10px" face="Palatino Linotype, Book Antiqua, Palatino, serif">
				Caro(a)  '.$linha['nomeCompleto'].'<br/>
É com Alegria que temos seu nome entre um dos nossos Peregrinos.  <br/>
E consta de seu cadastro que seu Passaporte vencerá no dia '.$this->convdata($linha['dataValidadePassaporte'],"mtn").'.<br/>
Caso necessite de alguma cooperação para a Renovação do mesmo, não hesite em nos contatctar.<br/>
Para consultar nossas Peregrinações, fineza acessar a página: <br/>
<a href="http://www.obrademariadf.com.br">www.obrademariadf.com.br</a><br/>
Abraço fraterno <br/>
Comunidade Obra de Maria<br/>
Agência de Peregrinações<br/>
www.obrademariadf.com.br<br/>
soraya@obrademariadf.com.br<br/>
fones.: 5555-5555
</font></td>
			  </tr>
			</table>';

			if($this->mail_html($linha['email'],$this->REMETENTE, 'Aviso de vencimento de passaporte', $email))
			$log .= $linha['nomeCompleto']." - Enviado<br/>";
			else
			$log .= $linha['nomeCompleto']." - Falha no envio<br/>";
			}
			$this->mail_html($this->destinatarios,$this->REMETENTE, 'Aviso de vencimento de passaporte', $log);

	$this->data = $hoje;
	$this->save();
	}
	}
 }


 function enviarEmailsChegadaGrupo(){
 	$hoje = date("Y-m-d");
	$hojets = strtotime($hoje);
	$this->getById(5);
	$ultimoDiats = strtotime($this->data);
	$dia = date("Y-m-d",$ultimoDiats);
	$i = 1;
	$dia =  date("Y-m-d", mktime(0,0,0,date("m",$ultimoDiats),date("d",$ultimoDiats)+$i,date("Y",$ultimoDiats)));
	$i = 2;
	while(str_replace("-","",$dia) <= str_replace("-","",$hoje)){
	$sql  ="select c.*, g.nomePacote from ag_cliente c inner join ag_participante p on p.cliente = c.id inner join ag_grupo g on g.id = p.grupo where g.dataChegada = '$dia'  and email != '' group by c.id";

			$rs = $this->DAO_ExecutarQuery($sql);
			$log = "Email de Chegada do grupo para: <br/>";
			$contGrupo = 0;
			while($linha = $this->DAO_GerarArray($rs)){
			$email = '<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
			  <tr>
				<td background="http://www.obrademariadf.com.br/admin/docs/cartao2.jpg" height="400" align="center"><font color="#FFFFFF" size="+2" face="Palatino Linotype, Book Antiqua, Palatino, serif">'.$linha['nomeCompleto'].'</font>
				<br/><font color="#FFFFFF" size="2" face="Palatino Linotype, Book Antiqua, Palatino, serif">
				Como foi Maravilhoso ter contado com sua Presença nesta Peregrinação Inesquecível !!</font>
				</td>
			  </tr>
			</table>';

			if($this->mail_html($linha['email'],$this->REMETENTE, 'Obra de Maria', $email))
			$log .= $linha['nomePacote']."-".$linha['nomeCompleto']." - Enviado<br/>";
			else
			$log .= $linha['nomePacote']."-".$linha['nomeCompleto']." - Falha no envio<br/>";
			$contGrupo++;
			}
			if($contGrupo > 0){
			$this->mail_html($this->destinatarios,$this->REMETENTE, 'Agendamento de Chegada de Grupo', $log);
			}
	$dia =  date("Y-m-d", mktime(0,0,0,date("m",$ultimoDiats),date("d",$ultimoDiats)+$i,date("Y",$ultimoDiats)));
	$i++;
	}//fim do loop de dias
	$this->data = $hoje;
	$this->save();
 }

}
?>