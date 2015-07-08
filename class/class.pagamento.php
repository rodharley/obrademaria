<?php

class formasPagamento extends persistencia{


var $idPagamentoForma =0;
var $txtNome;
var $txtImagem;
var $estatus;




function printFormRealizarPagamento($objFormasPagamento,$valorCobranca,$objVisa,$objLoja,$objCliente,$objRedeCard,$objBoleto,$objParcela,$objAmericanExpress,$REQUEST,$urlImg,$objPagSeguro,$idFormaPgt,$idPedido,$endereco,$SESSION,$objPedido,$idFilial,$objCielo){
//$objPedido->getById($SESSION['idPedido']);


switch($idFormaPgt){
case 1:
$this->efetuarPgtDinheiro($REQUEST['troco'],$objLoja->idLoja,$_SESSION['idPedido']);
$objPedido->salvarTerceorpPasso($_SESSION['idPedido']);

if(isset($valorCobranca))
return '<script>window.document.location.href="index.php?pag=includes/finalizar/pedidoFinalizado.php"</script>';
break;
case 12:
if(isset($valorCobranca))
$urlvolta = "escolheEnderecoCobranca.php";
else
$urlvolta = "escolheEnderecoEntrega.php";
return '<br><A href="index.php?urlContent='.$urlvolta.'">
			<IMG src="'.$urlImg.'bt/bt_voltar.jpg" border="0" ></A>&nbsp;&nbsp;&nbsp; 
			<IMG src="'.$urlImg.'bt/bt_finalizar_compra.jpg" border="0" onclick="window.document.formPgtDinheiro.submit();" style="cursor:pointer;">';
break;
case 2:
/*	return '
	
	<script language="JavaScript">

var retorno;

var mpg_popup;

window.name="loja";

function fabrewin(){



         if(navigator.appName.indexOf("Netscape") != -1) {

         mpg_popup=window.open("","mpg_popup","toolbar=0,location=0,directories=0,status=1,menubar=0,scrollbars=1,resizable=0,screenX=0,screenY=0,left=0,top=0,width=800,height=600");

          }

         else {

mpg_popup=window.open("","mpg_popup","toolbar=0,location=0,directories=0,status=1,menubar=0,scrollbars=1,resizable=1,screenX=0,screenY=0,left=0,top=0,width=800,height=600");

          }

         window.location="index.php?pag=includes/finalizar/fechar.php";

         return true; 

}

</script>  







 	<form name="frmpagamento" method="post" action="index.php?pag=includes/finalizar/efetuarPagamentoVisa.php" target="mpg_popup" onSubmit="javascript:fabrewin();"> 
<div style="width:800px; text-align:center" align="center">
<input type="image" src="http://portaldaalimentacao.com.br/praca/site/1/img/bt_finalizar_compra.jpg" onclick="window.document.frmpagamento.submit()" ></div>

</form> ';
*/
   //    $objVisa->efetuarPgtVisa($objLoja,$objPedido ->valorFrete+$objPedido ->valorTotal,$_SESSION,$objCliente,$_SESSION['idPedido']);
//return $objVisa->efetuarPgtVisa($objLoja,$objPedido ->valorFrete+$objPedido ->valorTotal+$objPedido ->
								  
							

return $objCielo->efetuarPgt($objLoja,$SESSION,$objCliente,$idPedido,$idFilial,$objPedido,$objFormaPagamento);


break;
case 5:
//
	
return $objCielo->efetuarPgt($objLoja,$SESSION,$objCliente,$idPedido,$idFilial,$objPedido,$objFormaPagamento);
break;
case 8:
return $objAmericanExpress->efetuarPgtAmerex($objLoja,$objPedido,$SESSION);
break;
case 13:
$objPedido->salvarTerceorpPasso($_SESSION['idPedido']);
if(isset($valorCobranca))
return '<script>window.document.location.href="index.php?pag=includes/finalizar/pedidoFinalizado.php"</script>';
break;
case 15:
$objPedido->salvarTerceorpPasso($_SESSION['idPedido']);
if(isset($valorCobranca))
return '<script>window.document.location.href="index.php?pag=includes/finalizar/pedidoFinalizado.php"</script>';
break;
case 14:
$objPedido->salvarTerceorpPasso($_SESSION['idPedido']);
if(isset($valorCobranca))
return '<script>window.document.location.href="index.php?pag=includes/finalizar/pedidoFinalizado.php"</script>';
break;

}
}





function setFormaPagamento($txtNome,$txtImagem,$estatus){
$this->setCampo($txtNome,"txtNome");
$this->setCampo($txtImagem,"txtImagem");
$this->setCampo($estatus,"estatus");
return true;
}

//funcao que salva um usuario
function saveformaPagamento(){
	if($this->idPagamentoForma != 0){
$recset = $this->makeSQL("update tb16_pgtformas set
txtNome  = '".$this->txtNome."',
estatus    = ".$this->estatus.",
txtImagem    = ".$this->txtImagem."
where idPagamentoForma  = ".$this->idPagamentoForma);
}else{
$recset = $this->makeSQL("insert into tb16_pgtformas
(txtNome,estatus,txtImagem)
values('".$this->txtNome."',".$this->estatus.",".$this->txtImagem.")");
}
return true;
}


function getById($id){
$this->idPagamentoForma = $id;
	$qry = "select * from tb16_pgtformas where idPagamentoForma = ".$this->idPagamentoForma;
	$recset = $this->makeSQL($qry);
	$r = mysql_fetch_array($recset);
	$this->setFormaPagamento($r['txtNome'],$r['txtImagem'],$r['estatus']);
	return true; 
}




function delFormaPagamento(){
$this->estatus = 0;
$this->saveformaPagamento();
return true;
}


function listaTodasFormasPgt(){
$planos = $this->makeSQL("select * from tb16_pgtformas where estatus = 1 order by txtNome");
return $this->listObject($planos);
}

function habilitaFormas($REQUEST,$idLoja,$idFilial){
$arrayFormas = $REQUEST['pgtFormas'];
$del = $this->makeSQL("update tb15_pgtformalojas set estatus = 0 where idLoja = ".$idLoja." and idFilial = ".$idFilial);

for($i=0;$i<count($arrayFormas);$i++){


$count = $this->makeSQL("select * from tb15_pgtformalojas where idLoja = ".$idLoja." and idFilial = ".$idFilial." and idPgtForma = ".$arrayFormas[$i]);

if(mysql_num_rows($count)){
$in = $this->makeSQL("update tb15_pgtformalojas set estatus = 1, DiaVencimento = '".$REQUEST['DiaVencimento'.$arrayFormas[$i]]."' where idLoja = ".$idLoja." and idFilial = ".$idFilial." and idPgtForma = ".$arrayFormas[$i]);
}else{
$in = $this->makeSQL("insert into tb15_pgtformalojas (estatus,idLoja,idPgtForma,DiaVencimento,idFilial)
values(1,".$idLoja.",".$arrayFormas[$i].",'".$REQUEST['DiaVencimento'.$arrayFormas[$i]]."','".$idFilial."')");
}
}
return true;
}




function FormasPgtporLojaClienteForma($Formas,$idLoja){


$count = $this->makeSQL("select * from tb15_pgtformalojas where idLoja = ".$idLoja." and idPgtForma = ".$Formas);

return $this->listObject($count);

}

function FormasPgtporLoja($idLoja){
$planos = $this->makeSQL("select * from tb15_pgtformalojas where estatus = 1 and idLoja = ".$idLoja);
return $this->listObject($planos);
}

function idFormasPgtporLoja($idLoja){
$array1 = array();
$planos = $this->makeSQL("select idPgtForma from tb15_pgtformalojas where estatus = 1 and idLoja = ".$idLoja.' and idFilial=0');
while($p=mysql_fetch_array($planos)){
array_push($array1,$p['idPgtForma']);
}
return $array1; 

}

function idFormasPgtporLojaFilial($idLoja,$idFilial){
$array1 = array();
$planos = $this->makeSQL("select idPgtForma from tb15_pgtformalojas where estatus = 1 and idLoja = ".$idLoja." and idFilial = ".$idFilial);


while($p=mysql_fetch_array($planos)){
array_push($array1,$p['idPgtForma']);
}
return $array1; 

}


function getFormasPgtporLoja($idLoja,$idFilial){


$formas = $this->makeSQL("select f.*,l.idFilial from tb15_pgtformalojas l inner join tb16_pgtformas f
on f.idPagamentoForma = l.idPgtForma where l.estatus = 1 and idLoja = ".$idLoja." and idFilial = '".$idFilial."' order by f.txtDescricaoForma");



if(mysql_num_rows($formas)>0){	
return $this->listObject($formas);
}else{
$formas = $this->makeSQL("select f.*,l.idFilial from tb15_pgtformalojas l inner join tb16_pgtformas f
on f.idPagamentoForma = l.idPgtForma where l.estatus = 1 and idLoja = ".$idLoja." and idFilial = 0   order by f.txtDescricaoForma");
return $this->listObject($formas);
}




}

function getFormasPgtporLojaPorFrete($idLoja,$tipo){



if ($tipo != "S")
$formas = $this->makeSQL("select f.* from tb15_pgtformalojas l inner join tb16_pgtformas f
on f.idPagamentoForma = l.idPgtForma where l.estatus = 1 and idLoja = ".$idLoja."   order by f.txtDescricaoForma");
else
$formas = $this->makeSQL("select f.* from tb15_pgtformalojas l inner join tb16_pgtformas f
on f.idPagamentoForma = l.idPgtForma where l.estatus = 1 and idLoja = ".$idLoja."  and f.idPagamentoForma != 1 order by f.txtDescricaoForma");

return $this->listObject($formas);
}

function printEscolhaFormaPagamento($objLoja,$valorTotal, $valorFrete,$SESSION,$url,$objParcela,$embalagem,$idFilial){

echo '<option value="" >Escolha forma de pagamento</option>';


  $formas = $this->getFormasPgtporLoja($objLoja->idLoja,$idFilial);
  
  //$corAba = 'abaCinza';
  for($i=0;$i<count($formas);$i++){
  
  if($SESSION['idFormaPgt']==$formas[$i]['idPagamentoForma'])
  $sele = 'selected="selected"';
  else
  $sele = '';		
 echo '<option value="'.$formas[$i]['idPagamentoForma'].'" '.$sele.'>'.$formas[$i]['idPagamentoForma'].' - '.$formas[$i]['txtNome'].'</option>';
  
	 }	
	



for($i=0;$i<count($formas);$i++){
$display = "display:none;";
		if(isset($SESSION['idFormaPgt'])){
		if($SESSION['idFormaPgt'] == $formas[$i]['idPagamentoForma'])
			$display = "display:";
	}
		
	if($formas[$i]['idPagamentoForma'] == 1 || $formas[$i]['idPagamentoForma'] == 13 || $formas[$i]['idPagamentoForma'] == 14 || $formas[$i]['idPagamentoForma'] == 15){
	$string .= '<input name="pgtEscolhido" type="hidden" value="1" />
	
	<div class="direita" style="'.$display.'" id="abaPagamento'.$formas[$i]['idPagamentoForma'].'">
							<table>
								<tbody><tr><td colspan="4" class="titulo"> '.$formas[$i]['txtNome'].'</td>
								</tr>
								';
								
								//	<img src="'.$url.'site/1/img/'.$formas[$i]['txtImagem'].'" border="0" title="'.$formas[$i]['txtNome'].'" align="absmiddle" width="100">
	}else{
		
		//<img src="'.$url.'site/1/img/'.$formas[$i]['txtImagem'].'" border="0" title="'.$formas[$i]['txtNome'].'" align="absmiddle" width="100"> 
	$string .= '<div class="direita" style="'.$display.'" id="abaPagamento'.$formas[$i]['idPagamentoForma'].'">
							<table>
								<tbody><tr><td colspan="4" class="titulo">
										'.$formas[$i]['txtNome'].'
									</td>
								</tr>
								<tr>
								<td class="subTitulo">&nbsp;</td>
									<td class="subTitulo" height="23">&nbsp;</td>
									<td class="subTitulo">Número de Parcelas</td>
									<td class="subTitulo">Valor de cada Parcela</td>
								</tr>';
	
	}
//monta visa eletron e 1x para o VISA

			if($formas[$i]['idPagamentoForma'] == 2){
				
			
				
			
$cielostris = $this->makeSQL("SELECT * FROM tb16_configuracao_cielo where  idLoja = ".$objLoja->idLoja."   and idFilial='".$idFilial."'");		
if(mysql_num_rows($cielostris)==0)
$cielostris = $this->makeSQL("SELECT * FROM tb16_configuracao_cielo where  idLoja = ".$objLoja->idLoja."   and idFilial=0");



$ci = mysql_fetch_array($cielostris);
				
				
			if($SESSION['transacao'] == 'A')
					$checked = 'checked="checked"';
					else
					$checked = '';
					
			        $string .= ' <tr>
			
			<td rowspan="12"><img src="'.$url.'site/1/img/'.$formas[$i]['txtImagem'].'" border="0" title="'.$formas[$i]['txtNome'].'" align="absmiddle" width="100"></td>';
			if($ci['stDebito']==1){
			
				
					   $string .= '<td height="23"class="conteudo">
					<input name="pgtEscolhido" type="radio" value="VisaEletron" onclick="window.document.getElementById(\'idParcela\').value = this.value;" '.$checked.'/></td>
					<td class="conteudo">Visa Eletron (Bradesco) </td>
					<td class="conteudo">R$ '.$this->money($valorTotal+$valorFrete+$embalagem,"atb").'</td>';
			}else{
				
					$SESSION['transacao'] =1;
				}
				$string .= ' </tr>';
			if($SESSION['transacao'] == '1')
					$checked = 'checked="checked"';
					else
					$checked = '';

				$string .='<tr>
					<td height="23" class="conteudo">
					<input name="pgtEscolhido" type="radio" value="VisaAVista" onclick="window.document.getElementById(\'idParcela\').value = this.value;" '.$checked.'/></td>
					<td class="conteudo">Crédito </td>
					<td class="conteudo">R$ '.$this->money($valorTotal+$valorFrete+$embalagem,"atb").'</td>
				  </tr>';
			}
			if($formas[$i]['idPagamentoForma'] == 1){
			if($SESSION['idFormaPgt'] == '1')
					$checked = 'checked="checked"';
					else
					$checked = '';
			$string .= ' 
				  <tr>
				  <td rowspan="12"><img src="'.$url.'site/1/img/'.$formas[$i]['txtImagem'].'" border="0" title="'.$formas[$i]['txtNome'].'" align="absmiddle" width="100"></td>
					<td height="23" class="conteudo"></td>
					<td class="conteudo" align="right"><strong>Troco? </strong></td>
					<td class="conteudo" align="left"><input type="radio" name="stTroco" value="1" onclick="window.document.getElementById(\'troco\').style.display = \'\'"  />Sim &nbsp;<input type="radio" value="0"  name="stTroco" onclick="window.document.getElementById(\'troco\').style.display = \'none\';"  />N&atilde;o</td>
				  </tr>
				   <tr id="troco" style="display:none">
					<td height="23" class="conteudo"></td>
					<td class="conteudo" align="right"><strong>Troco Para: </strong></td>
					<td class="conteudo" align="left"><input type="text"  size="7" maxlength="7" name="troco2"  class="inputBotao" onKeyUp="moeda(this);funcao2(this.value)"/></td>
				  </tr>
				  
				  ';
			}
			
			
			if($formas[$i]['idPagamentoForma'] == 5){
			if($SESSION['transacao'] == '04')
					$checked = 'checked="checked"';
					else
					$checked = '';
			$string .= ' <tr>
			
			<td rowspan="12"><img src="'.$url.'site/1/img/'.$formas[$i]['txtImagem'].'" border="0" title="'.$formas[$i]['txtNome'].'" align="absmiddle" width="100"></td>
			
					<td height="23" class="conteudo">
					<input name="pgtEscolhido" type="radio" value="MasterAVista" onclick="window.document.getElementById(\'idParcela\').value = this.value;" '.$checked.'/></td>
					<td class="conteudo">Crédito </td>
					<td class="conteudo">R$ '.$this->money($valorTotal+$valorFrete+$embalagem,"atb").'</td>
				  </tr>';  
			}
			if($formas[$i]['idPagamentoForma'] == 6){
			if($SESSION['transacao'] == '04')
					$checked = 'checked="checked"';
					else
					$checked = '';
			$string .= ' <tr>
			
			<td rowspan="12"><img src="'.$url.'site/1/img/'.$formas[$i]['txtImagem'].'" border="0" title="'.$formas[$i]['txtNome'].'" align="absmiddle" width="100"></td>
			
					<td height="23" class="conteudo">
					<input name="pgtEscolhido" type="radio" value="DinnersAVista" onclick="window.document.getElementById(\'idParcela\').value = this.value;" '.$checked.'/></td>
					<td class="conteudo">Crédito </td>
					<td class="conteudo">R$ '.$this->money($valorTotal+$valorFrete+$embalagem,"atb").'</td>
				  </tr>';
				  
			}
			if($formas[$i]['idPagamentoForma'] == 8){
			/*if($SESSION['transacao'] == 'DEBIT')
					$checked = 'checked="checked"';
					else
					$checked = '';
			$string .= ' <tr>
					<td height="23" class="conteudo">
					<input name="pgtEscolhido" type="radio" value="AmexDebito" onclick="window.document.getElementById(\'idParcela\').value = this.value;" '.$checked.'/></td>
					<td class="conteudo">Débito em conta </td>
					<td class="conteudo">R$ '.$this->money($valorTotal+$valorFrete,"atb").'</td>
				  </tr>';*/
			if($SESSION['transacao'] == 'CREDIT')
					$checked = 'checked="checked"';
					else
					$checked = '';
			$string .= ' <tr>
			
			<td rowspan="12"><img src="'.$url.'site/1/img/'.$formas[$i]['txtImagem'].'" border="0" title="'.$formas[$i]['txtNome'].'" align="absmiddle" width="100"></td>
					<td height="23" class="conteudo">
					<input name="pgtEscolhido" type="radio" value="AmexAVista" onclick="window.document.getElementById(\'idParcela\').value = this.value;" '.$checked.'/></td>
					<td class="conteudo">Crédito </td>
					<td class="conteudo">R$ '.$this->money($valorTotal+$valorFrete+$embalagem,"atb").'</td>
				  </tr>';	  
			}
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			if($formas[$i]['idPagamentoForma'] == 13){
				$string .= 	$string .= '<tr>
			<td rowspan="12"><img src="'.$url.'site/1/img/'.$formas[$i]['txtImagem'].'" border="0" title="'.$formas[$i]['txtNome'].'" align="absmiddle" width="100"></td>
			<td > Levaremos uma maquineta para voc&ecirc; <br>para que seja efetuado o pagamento. <br><br><strong>Finalize a compra!!</strong></td>
				  </tr>';	;		  
			}
			
			
			
			
			
			
			
				
			if($formas[$i]['idPagamentoForma'] == 15){		
	
			$string .= '<tr>
			<td rowspan="12"><img src="'.$url.'site/1/img/'.$formas[$i]['txtImagem'].'" border="0" title="'.$formas[$i]['txtNome'].'" align="absmiddle" width="100"></td>
			<td > Levaremos uma maquineta para voc&ecirc; <br>para que seja efetuado o pagamento. <br><br><strong>Finalize a compra!!</strong></td>
				  </tr>';	  
			}
			
			
			
			
			if($formas[$i]['idPagamentoForma'] == 14){		
	
			$string .= '<tr>
			<td rowspan="12"><img src="'.$url.'site/1/img/'.$formas[$i]['txtImagem'].'" border="0" title="'.$formas[$i]['txtNome'].'" align="absmiddle" width="100"></td>
			<td > Levaremos uma maquineta para voc&ecirc; <br>para que seja efetuado o pagamento. <br><br><strong>Finalize a compra!!</strong></td>
				  </tr>';	  
			}
			
			
			
			//gerando forma a vista de boleto bradesco
		
		
			
			
		
			
		$parcelas = $objParcela->getParcelasLojaValorMinimo($objLoja->idLoja,$formas[$i]['idPagamentoForma'],$valorTotal+$valorFrete+$embalagem,$formas[$i]['idFilial']);
	
			for($p=0;$p<count($parcelas);$p++){
				$aceita =1;
				if($parcelas[$p]['tipoParcela'] == 0){
				$situacaoCarrinho = $objCarrinho->seAceitaParcelamento($parcelas[$p]['idPgtParcela'],$sessionId);
				$aceita= $situacaoCarrinho;
				}
				if($aceita==1){
					if($SESSION['idParcelaPgt'] == $parcelas[$p]['idPgtParcela'])
					$checked = 'checked="checked"';
					else
					$checked = '';
					$descricao = $objParcela->getDescricaoParcela($parcelas[$p]['idPgtParcela']);
					$valorParcela = $objParcela->getValorParcela($parcelas[$p]['idPgtParcela'],($valorTotal+$valorFrete+$embalagem));
					$string .= ' <tr>
					<td height="23" class="conteudo">
					<input name="pgtEscolhido" type="radio" value="'.$parcelas[$p]['idPgtParcela'].'" onclick="window.document.getElementById(\'idParcela\').value = this.value;" '.$checked.'/></td>
					<td class="conteudo">'.$descricao.' </td>
					<td class="conteudo">R$ '.$this->money($valorParcela,"atb").'</td>
				  </tr>';
				  	}
		  		}		
				$string .= '</tbody></table>';
				$string .=  '</div>';
				}
			
				$string .=	'</div>
					
					<div class="bordaInferiorEsquerda"></div>
					<div class="bordaInferiorDireita"></div>
					<div class="linhaInferior"></div>
				</div>
			</div>';
if(isset($SESSION['idParcelaPgt']))
$string .= '<input type="hidden" name="idParcela" id="idParcela" value="'.$SESSION['idParcelaPgt'].'">';
else
$string .= '<input type="hidden" name="idParcela" id="idParcela" value="0">';

 return $string;

}

function setFormaPagamentEscolhido($REQUEST,$objParcela,$objLoja){


if(isset($REQUEST['idParcela'])){
unset($_SESSION['idParcelaPgt']);
unset($_SESSION['idFormaPgt']);
unset($_SESSION['transacao']);
unset($_SESSION['parcela']);
unset($_SESSION['vpc_PaymentPlan']);
if($REQUEST['idParcela'] == 'VisaEletron'){
$_SESSION['idFormaPgt'] = 2;
$_SESSION['transacao'] = 'A';
$_SESSION['parcela'] = '001';
$_SESSION['idParcelaPgt'] = 'VisaEletron';
}else if($REQUEST['idParcela'] == 'VisaAVista'){
$_SESSION['idFormaPgt'] = 2;
$_SESSION['transacao'] = '1';
$_SESSION['parcela'] = '001';
$_SESSION['idParcelaPgt'] = 'VisaAVista';
}else if($REQUEST['idParcela'] == 'MasterAVista'){
$_SESSION['idFormaPgt'] = 5;
$_SESSION['transacao'] = '04';
$_SESSION['parcela'] = '00';
$_SESSION['idParcelaPgt'] = 'MasterAVista';
}else if($REQUEST['idParcela'] == 'DinnersAVista'){
$_SESSION['idFormaPgt'] = 6;
$_SESSION['transacao'] = '04';
$_SESSION['parcela'] = '00';
$_SESSION['idParcelaPgt'] = 'DinnersAVista';
}else if($REQUEST['idParcela'] == 'AmexAVista'){
$_SESSION['idFormaPgt'] = 8;
$_SESSION['transacao'] = 'CREDIT';
$_SESSION['parcela'] = '1';
$_SESSION['vpc_PaymentPlan'] = '';
$_SESSION['idParcelaPgt'] = 'AmexAVista';
}else if($REQUEST['idParcela'] == 'AmexDebito'){
$_SESSION['idFormaPgt'] = 8;
$_SESSION['transacao'] = 'DEBIT';
$_SESSION['parcela'] = '1';
$_SESSION['vpc_PaymentPlan'] = '';
$_SESSION['idParcelaPgt'] = 'AmexDebito';
}else if($REQUEST['idParcela'] == 'Dinheiro'){
$_SESSION['idFormaPgt'] = 1;
$_SESSION['idParcelaPgt'] = 'Dinheiro';
return true;
}else if($REQUEST['idParcela'] == 'BoletoaVista'){
$_SESSION['idFormaPgt'] = 4;
$_SESSION['idParcelaPgt'] = 'BoletoaVista';
}else if($REQUEST['idParcela'] =='BoletoaVistaBB'){
$_SESSION['idFormaPgt'] = 7;
$_SESSION['idParcelaPgt'] = 'BoletoaVistaBB';
}else if($REQUEST['idParcela'] =='BoletoaVistaC'){
$_SESSION['idFormaPgt'] = 9;
$_SESSION['idParcelaPgt'] = 'BoletoaVistaC';
}else if($REQUEST['idParcela'] =='BoletoaVistaH'){
$_SESSION['idFormaPgt'] = 10;
$_SESSION['idParcelaPgt'] = 'BoletoaVistaH';
}else if($REQUEST['idParcela'] =='PagSeguro'){
$_SESSION['idFormaPgt'] = 11;
$_SESSION['idParcelaPgt'] = 'PagSeguro';
}else if($REQUEST['idParcela'] == 'Deposito'){
$_SESSION['idFormaPgt'] = 12;
$_SESSION['idParcelaPgt'] = 'Deposito';

}else{
$objParcela->getById($REQUEST['idParcela']);
		$_SESSION['idFormaPgt'] = $objParcela->idPgtForma;
		$_SESSION['idParcelaPgt'] = $objParcela->idPgtParcela;
		if($objParcela->idPgtForma == 2){
			$_SESSION['transacao'] = '2';
			//$_SESSION['parcela'] = str_pad($objParcela->intNParcela,3,'0',STR_PAD_LEFT);
			$_SESSION['parcela'] = $objParcela->intNParcela;
		}
		if($objParcela->idPgtForma == 5 || $objParcela->idPgtForma == 6){
			$_SESSION['transacao'] = '06';
			$_SESSION['parcela'] = $objParcela->intNParcela;
				//$_SESSION['parcela'] = str_pad($objParcela->intNParcela,2,'0',STR_PAD_LEFT);
		}
		if($objParcela->idPgtForma == 8){
			$_SESSION['transacao'] = 'CREDIT';
			$_SESSION['parcela'] = $objParcela->intNParcela;
			if($objParcela->decJuros == 0)
			$_SESSION['vpc_PaymentPlan'] = 'PlanN';
			else
			$_SESSION['vpc_PaymentPlan'] = 'PlanAmex';
		}
}
}
return true;
}



function efetuarPgtDinheiro($troco,$idLoja,$idPedido){
if(isset($troco)){
$troco = $this->money($troco,"bta");
$query = "select idPgtDinheiro from tb16_pgtpedidodinheiro 
where idLoja = ".$idLoja." and idPedido = ".$idPedido;
$test = $this->makeSQL($query);
if(mysql_num_rows($test))
$query = "update tb16_pgtpedidodinheiro set decTroco = ".$troco." where idLoja = ".$idLoja." and idPedido = ".$idPedido;
else
$query = "insert into tb16_pgtpedidodinheiro (decTroco, idLoja,idPedido)
values (".$troco.",".$idLoja.",".$idPedido.")";
$this->makeSQL($query);
}
return true;
}










}
//PARCELAS #####################################################################################################
################################################################################################################
class parcela extends conexao{
var $idPgtParcela =0;
var $intNParcela;
var $decValorMinimo;
var $decJuros;
var $idPgtForma;
var $idLoja;
var $tipoParcela=1;
var $numeroAutorizacao=1;
var $idFilial;

function setParcelaLoja($intNParcela,$decValorMinimo,$decJuros,$idPgtForma,$idLoja,$tipoParcela,$numeroAutorizacao,$idFilial){
$this->setCampo($intNParcela,'intNParcela');
$this->setCampo($decValorMinimo,'decValorMinimo');
$this->setCampo($decJuros,'decJuros');
$this->setCampo($idPgtForma,'idPgtForma');
$this->setCampo($idLoja,'idLoja');
$this->setCampo($tipoParcela,'tipoParcela');
$this->setCampo($numeroAutorizacao,'numeroAutorizacao');
$this->setCampo($idFilial,'idFilial');


return true;
}

function saveParcelaLoja(){
if($this->idPgtParcela != 0){
$this->makeSQL("update tb16_pgtparcelas 
set
intNParcela = ".$this->intNParcela.",          
decJuros = ".$this->decJuros.",
decValorMinimo= ".$this->decValorMinimo.",
idLoja= ".$this->idLoja.",
tipoParcela= ".$this->tipoParcela.",
numeroAutorizacao = '".$this->numeroAutorizacao."',
idFilial = '".$this->idFilial."',
idPgtForma= ".$this->idPgtForma."
where idPgtParcela = ".$this->idPgtParcela);


}else{
$this->makeSQL("insert into tb16_pgtparcelas
(intNParcela,decJuros,decValorMinimo,idLoja,idPgtForma,tipoParcela,numeroAutorizacao,idFilial)
values
(".$this->intNParcela.",".$this->decJuros.",".$this->decValorMinimo.",".$this->idLoja.",".$this->idPgtForma.",".$this->tipoParcela.",'".$this->numeroAutorizacao."','".$this->idFilial."')");


}
return true;
}
function getById($id){
if ($id==""){
	mail("desenvolvimento@msigroup.com.br","Erro no pagamento parcelado","Novo E-commerce");
	return false;
}
$this->idPgtParcela = $id;
	$qry = "select * from tb16_pgtparcelas where idPgtParcela = ".$this->idPgtParcela;
	$recset = $this->makeSQL($qry);
	$r = mysql_fetch_array($recset);
	$this->setParcelaLoja($r['intNParcela'],$r['decValorMinimo'],$r['decJuros'],$r['idPgtForma'],$r['idLoja'],$r['tipoParcela'],$r['numeroAutorizacao'],$r['idFilial']);
	return true;
}
function getParcelasLoja($idLoja,$idPgtForma,$idFilial){
if(!$idFilial)	
$idFilial = 0;	
$planos = $this->makeSQL("select * from tb16_pgtparcelas where idLoja = ".$idLoja."  and idFilial = ".$idFilial."  and idPgtForma = ".$idPgtForma." order by intNParcela");


return $this->listObject($planos);
}

function getParcelasLojaValorMinimo($idLoja,$idPgtForma,$valorCompra,$idFilial){
$planos = $this->makeSQL("select * from tb16_pgtparcelas where idLoja = ".$idLoja." and idFilial = ".$idFilial." and idPgtForma = ".$idPgtForma." and decValorMinimo <= ".$valorCompra." order by intNParcela ");
if(mysql_num_rows($planos)==0){
$planos = $this->makeSQL("select * from tb16_pgtparcelas where idLoja = ".$idLoja." and idFilial = 0 and idPgtForma = ".$idPgtForma." and decValorMinimo <= ".$valorCompra." order by intNParcela ");	
	}

return $this->listObject($planos);
}

function getParcelasPorProdutoLoja($idLoja,$idPgtForma){
$planos = $this->makeSQL("select * from tb16_pgtparcelas where idLoja = ".$idLoja." and idPgtForma = ".$idPgtForma." and tipoParcela = 0 order by intNParcela");
return $this->listObject($planos);
}

function delParcelaLoja(){
$this->makeSQL("delete from tb16_pgtparcelas where idPgtParcela = ".$this->idPgtParcela);
return true;
}

function isParcelaLoja($parcela,$idPgtForma,$idLoja,$idFilial){
$p = $this->makeSQL("select * from tb16_pgtparcelas where idLoja = ".$idLoja." and idFilial = ".$idFilial." and idPgtForma = ".$idPgtForma." and intNParcela = ".$parcela);
if(mysql_num_rows($p))
return true;
else
return false;
}
//RELACIONAMENTO DE PRODUTOS E PARCELAS
function getParcelasDeProduto($idProduto){
$array1 = array();
$planos = $this->makeSQL("select idParcela from et_pgtParcelaProduto where idProduto = ".$idProduto);
while($f = mysql_fetch_array($planos)){
array_push($array1,$f['idParcela']);
}
return $array1;
}


function getDescricaoParcela($idParcela){
$this->getById($idParcela);
$string = $this->intNParcela.'x ';
if($this->decJuros  == 0)
$string .= 'Sem Juros';
else
$string .= 'Com Juros';
return $string;
}



function getValorParcela($idParcela,$valorTotal){
$this->getById($idParcela);
if($this->decJuros  == 0)
return number_format(($valorTotal/$this->intNParcela),2,".","");
else
return number_format(((($this->decJuros/100)*$valorTotal)+$valorTotal)/$this->intNParcela,2,".","");
}
}







?>
