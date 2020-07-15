<?php
include("tupi.inicializar.php");
$codAcesso = 38;
include("tupi.seguranca.php");


// add records to the log
//$log->warning('Foo');
//$log->error('Bar');

set_time_limit (0);
$oGrupo = new Grupo();
$oMoeda = new Moeda();
$oPagamento = new Pagamento();
$oAbatimento = new Abatimento();
$oTP = new TipoPagamento();
$oTT = new TipoTransferencia();
$oCheque = new Cheque();

$oGrupo->getById($oGrupo->md5_decrypt($_REQUEST['idGrupo']));

//recupera participantes aprovados
$opartic = new Participante();
$de = $_REQUEST['de']-1;
$qtdate = $_REQUEST['ate']-$de;

$rs = $opartic->participantesGrupo($oGrupo->id,$de,$qtdate);

$cont = $_REQUEST['de'];
$TOTAL_CUSTO_DOLLAR = 0;
$TOTAL_CUSTO_REAL =0;
$TOTAL_CARTAO = 0;
$TOTAL_ESPECIE =0;
$TOTAL_TRANSF =0;
$TOTAL_DEPOSITO =0;
$TOTAL_DEBITO =0;
$TOTAL_TED =0;
$TOTAL_DOC =0;
$TOTAL_CREDITO =0; 
$TOTAL_CHEQUE =0;
$TOTAL_RECEBIMENTO_DOLLAR =0;
$TOTAL_RECEBIMENTO_REAL =0;?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Relatório Geral por Grupo</title>

<link href="css/bootstrap.css" rel="stylesheet">
<script src="js/jquery-1.7.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/scripts.js"></script>
<style type="text/css">
.rodape {
 padding: 2px;
  margin-top: 10px;
  margin-bottom: 10px;
  background-color: #ffffff;
  border: 1px solid #333333;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  color: #000000;
  text-align:center;
  width:280mm;
}
.cabecalho {
 padding: 2px;
  margin-top: 10px;
  margin-bottom: 10px;
  background-color: #ffffff;
  border: 1px solid #333333;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  color: #000000;
  text-align:left;
  width:280mm;
}
.cabecalho .textoCabecalho{
text-align:right;
padding-top:5px;
padding-right:5px;
text-align:right;
font-size:10px;
  line-height:140%;
}
</style>
<style media="print">
.form-search{
display:none;
}
.well{
background-color:#FFFFFF;
}
.rodape {
 padding: 2px;
  margin-top: 10px;
  margin-bottom: 10px;
  background-color: #ffffff;
  border: 1px solid #333333;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  color: #000000;
  text-align:center;
  width:280mm;
}
.cabecalho {
 padding: 2px;
  margin-top: 10px;
  margin-bottom: 10px;
  background-color: #ffffff;
  border: 1px solid #333333;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  color: #000000;
  text-align:left;
  width:280mm;
}
.cabecalho .textoCabecalho{
text-align:right;
padding-top:5px;
padding-right:5px;
text-align:right;
font-size:10px;
  line-height:140%;
}

</style>
</head>
<body>
<div class="container" style="width:280mm;">
<div class="cabecalho">
<div class="row"><div class="span4"><img src="img/logoEmpresa.png" width="280" height="115" /></div><div class="textoCabecalho">RAINHA TOUR E VIAGENS LTDA<br/>
COMUNIDADE OBRA DE MARIA<br/>
CNPJ.: 12.415.719/0001-72<br/>
EMBRATUR.: 07.046409.10.0001-8<br/>
SRTVS - QUADRA 701 - BL II  -  SALA 208<br/>
CENTRO EMPRESARIAL ASSIS CHATEAUBRIAND<br/>
ASA SUL - BRASILIA - DF <br/>
CEP 70340.906<br/>
www.obrademariadf.com.br<br/>
brasilia@obrademaria.com.br<br/>
fone.fax.: 55 61 32015116
</div>
</div>
  </div>  
	<div id="content" style="width:280mm;">
		
		
		
		<style type="text/css">
.tableMin {
clear:both;
padding:1px;
}
.tableMin tr td {
font-size:8px;
}
.tableMin tr th {
font-size:8px;
}

</style>
<section>
<h3>Relatório Geral Por Grupo - <?="Data/Hora:" .date("d/m/Y h:i:s");?></h3>
<h3><?=str_pad($oGrupo->id,7,"0", STR_PAD_LEFT)."-".$oGrupo->nomePacote;?></h3>
<br />
<h4>Detalhes de Pagamentos</h4>    

<?
$linhaParticipantes = "";
foreach($rs as $key => $p){
				
				$listaCheques = "";
				$possuiCheques = 0;
				//calcula custo:
				$custo = $p->custoTotal;
				$cotCustpo = $oGrupo->cotacaoCusto  == 0 ? 1 : $oGrupo->cotacaoCusto;
				if($oGrupo->moeda->id == $oMoeda->DOLLAR()){
				$custoDollar = $custo;
				$custoReal = $custo * $oGrupo->cotacaoCusto;
				}else{
				$custoDollar = $custo / $cotCustpo;
				$custoReal = $custo;
				}
				
				
					//busca os abatimentos do participante
		
		$rsAbat = $oAbatimento->abatimentosParticipantes($p->id);
		
		$contPag = 0;
		$totalAbatParticipanteReal = 0;
		$totalAbatParticipanteDollar = 0;
			?>

 <div class="well">
        <table width="100%" class="table table-striped table-bordered table-condensed">			
			<thead>         
				<tr>
                <th colspan="12"><?=$cont?> - <?=$p->cliente->nomeCompleto?></th>				
          		</tr>
        	</thead>
			<thead>         
				<tr>
                <th colspan="12">Transações/Abatimentos</th>				
          		</tr>
        	</thead>
			<thead>         
				<tr>
				<th >Nº</th>
				<th >Transação</th>
				<th >Cliente Pagador</th>
                <th >Grupo Origem</th>
            	<th >Tipo</th>
				<th >Moeda</th>	
				<th> Data</th>		
            	<th >Valor Pag R$</th>
				<th >Valor Pag. US$</th>
            	<th >Cotação</th>
				<th >Valor Abat R$</th>
				<th >Valor Abat. US$</th>            	           
          		</tr>
        	</thead>
	        <tbody>
<?
foreach($rsAbat as $keyAbat => $abat){
		
		$contPag++;
		//busca o pagamento ativo do participante
		$oPagamento->getById($abat->pagamento->id);
		//somatorios dos valores
					switch($oPagamento->tipo->id){
					case $oTP->DINHEIRO():
					$TOTAL_ESPECIE += $abat->getValorReal();
					break;
					case $oTP->CARTAO():
					$TOTAL_CARTAO += $abat->getValorReal();
					break;
					case $oTP->DEBITO():
					$TOTAL_DEBITO += $abat->getValorReal();
					break;
					case $oTP->CHEQUE():
					$TOTAL_CHEQUE +=  $abat->getValorReal();
					$possuiCheques = 1;	
					break;
					case $oTP->CREDITO():
					$TOTAL_CREDITO +=  $abat->getValorReal();
					break;
					case $oTP->BANCO():
						$oTT->getById($oPagamento->tipoTransferencia->id);
						$tipoPag = $oTT->descricao;
						switch($oPagamento->tipoTransferencia->id){
							case $oTT->TRANSFERENCIA():
								$TOTAL_TRANSF +=  $abat->getValorReal();
							break;
							case $oTT->DEPOSITO():
								$TOTAL_DEPOSITO +=  $abat->getValorReal();
							break;
							case $oTT->TED():
								$TOTAL_TED +=  $abat->getValorReal();
							break;
							case $oTT->DOC():
								$TOTAL_DOC +=  $abat->getValorReal();
							break;
						}

					break;
					}

		$totalAbatParticipanteReal += $abat->getValorReal();
		$totalAbatParticipanteDollar += $abat->getValorDollar();
		
?>
		 <tr>
           <td ><?=$contPag?></td>
		   <td ><?=$oPagamento->devolucao == 0 ? "Crédito": "Débito"?></td>
		   <td ><?=$oPagamento->participante->cliente->nomeCompleto;?></td>
		   <td ><?=$oPagamento->participante->grupo->nomePacote;?></td>
           <td ><?=$oPagamento->tipo->descricao;?></td>			
           <td ><?=$oPagamento->moeda->descricao;?></td>
		   <td><?=$oMoeda->convdata($oPagamento->dataPagamento,"mtn");?></td>
		   <td style="text-align:right;"><?=$oMoeda->money($oPagamento->devolucao == 0 ? $oPagamento->CALCULA_REAL() : -$oPagamento->CALCULA_REAL(),"atb");?></td>
		   <td style="text-align:right;"><?=$oMoeda->money($oPagamento->devolucao == 0 ? $oPagamento->CALCULA_DOLLAR() : -$oPagamento->CALCULA_DOLLAR(),"atb");?></td>
		   <td style="text-align:right;"><?=$oMoeda->money($oPagamento->cotacaoReal,"atb")?></td>
		   <td style="text-align:right;"><?=$oMoeda->money($abat->getValorReal(),"atb");?></td>
		   <td style="text-align:right;"><?=$oMoeda->money($abat->getValorDollar(),"atb");?></td>
          </tr>
		   <? 
		   
		   
		   $rsCheques = $oCheque->getSQL("Select * from ag_cheque where idPagamento = ".$oPagamento->id);//$oCheque->getRows(0,999,array(),array("pagamento" => "=".$oPagamento->id));
		   
		   foreach($rsCheques as $keyCh => $cheque){ 
		   $listaCheques .= '<tr><td>'.$contPag.'</td><td>'.$oPagamento->banco->sigla.'</td>
  			<td >'.$cheque->emissor->nomeCompleto.'</td>
		   <td >'.$oMoeda->convdata($cheque->dataCompensacao,"mtn").'</td>
           <td >'.$cheque->numeroCheque.'</td>
           <td style="text-align:right;">'.$oMoeda->money($cheque->valor,"atb").'</td></tr>';
		   }
		   
		}//looop dos abatimentos
		   
		   $recebimentosDollar = $totalAbatParticipanteDollar;//$p->recuperaValorTodosAbatimentos($oMoeda->DOLLAR());
    	$recebimentosReal = $totalAbatParticipanteReal;//$p->recuperaValorTodosAbatimentos($oMoeda->REAL());
    //alimenta a primeira linha do relatorio
    //SOMATORIOS DE VALORES
    $TOTAL_RECEBIMENTO_DOLLAR +=  $recebimentosDollar;
    $TOTAL_RECEBIMENTO_REAL +=  $recebimentosReal;
    $TOTAL_CUSTO_DOLLAR +=  $custoDollar;
    $TOTAL_CUSTO_REAL +=  $custoReal;
 

		   
		   ?>
		    <tr>
           <td colspan="10" style="text-align:right;">Total:</td>
		   <td style="text-align:right;"><?=$oMoeda->money($totalAbatParticipanteReal,"atb");?></td>
		   <td style="text-align:right;"><?=$oMoeda->money($totalAbatParticipanteDollar,"atb");?></td>
          </tr>
          <?
		  if($possuiCheques == 1){
			?>
		  <tr>              
			   <th colspan="12">Cheques</th>				
          </tr>
		  <tr>
		  <td>&nbsp;</td>
		  <td colspan="12">
		  <table align="right" cellspacing="1" cellpadding="1" border="0" class="table table-striped table-bordered table-condensed">
		  <tr>
				<th>Nº Pag</th>
				<th>Banco</th>
				<th >Emissor</th>
				<th >Data de Compensação</th>
				<th >Número</th>
            	<th >Valor R$</th>								
          </tr>
		   <?=$listaCheques?>
		  </table>
		  </td>
		  </tr>
		  
		  <?}?>            
		 </tbody>
    </table>
  </div> 
  
  
  <? 
  
  $linhaParticipantes .= "<tr><td >$cont</td>
           <td >".$p->cliente->nomeCompleto."</td>
		   <td >".$p->status->descricao."</td>			
           <td style='text-align:right;'>".$oMoeda->money($custoReal,"atb")."</td>
		   <td style='text-align:right;'>".$oMoeda->money($custoDollar,"atb")."</td>
		   <td style='text-align:right;'>".$oMoeda->money($oGrupo->cotacaoCusto,"atb")."</td>
		   <td style='text-align:right;'>".$oMoeda->money($recebimentosReal,"atb")."</td>
		   <td style='text-align:right;'>".$oMoeda->money($recebimentosDollar,"atb")."</td></tr>";
		   
		   		  
  ?>
<?
$cont++;

}

?>   





<div class="well">
<h4>Participantes</h4>
<form class="well form-search" action="relatorio.listaCamisetaPrint.php" id="frmEmail" method="post">      
		<button class="btn btn-success" id="bt_print" type="button"><i class="icon-print icon-white"></i> Imprimir</button>
      </form>
<table class="table table-striped table-bordered table-condensed">
        <thead>         
		  <tr>
		  	<th >#</th>
            <th >Participante</th>
			<th >Status</th>			
            <th >Custo R$</th>
			<th >Custo US$</th>
			<th >Cotação</th>
			<th >Recebimentos R$</th>
			<th >Recebimentos US$</th>
            </tr>
        </thead>		
		<tbody>
			<?= $linhaParticipantes?>			
         <tr>
		   <td colspan="3" style="text-align:right;">Totais:</td>			
           <td style="text-align:right;"><?=$oMoeda->money($TOTAL_CUSTO_REAL,"atb");?></td>
		   <td style="text-align:right;"><?=$oMoeda->money($TOTAL_CUSTO_DOLLAR,"atb");?></td>
		   <td style="text-align:center;">-</td>
		   <td style="text-align:right;"><?=$oMoeda->money($TOTAL_RECEBIMENTO_REAL,"atb");?></td>
		   <td style="text-align:right;"><?=$oMoeda->money($TOTAL_RECEBIMENTO_DOLLAR,"atb");?></td>                       
          </tr>		  
         </tbody></table>
 </div>




   <div class="well">
<h4>Totais Por Tipo de Pagamento R$</h4>  
  <table class="table table-bordered table-condensed">  
		<thead>         
				<tr>
				<th >Cheque</th>
            	<th >Espécie</th>
				<th >Cartão</th>
				<th >Débito</th>
				<th >Depósito</th>			
            	<th >Transferência</th>
				<th >TED</th>
            	<th >DOC</th>
				<th >Crédito Cliente</th>           
          		</tr>
        	</thead>
	        <tbody>
		 	<tr>
			   <td style="text-align:right;">R$ <?=$oMoeda->money($TOTAL_CHEQUE,"atb");?></td>
			   <td style="text-align:right;">R$ <?=$oMoeda->money($TOTAL_ESPECIE,"atb");?></td>
			   <td style="text-align:right;">R$ <?=$oMoeda->money($TOTAL_CARTAO,"atb");?></td>	
			   <td style="text-align:right;">R$<?=$oMoeda->money($TOTAL_DEBITO,"atb");?></td>			
			   <td style="text-align:right;">R$ <?=$oMoeda->money($TOTAL_DEPOSITO,"atb");?></td>
			   <td style="text-align:right;">R$ <?=$oMoeda->money($TOTAL_TRANSF,"atb");?></td>
			   <td style="text-align:right;">R$ <?=$oMoeda->money($TOTAL_TED,"atb");?></td>
			   <td style="text-align:right;">R$ <?=$oMoeda->money($TOTAL_DOC,"atb");?></td>
			   <td style="text-align:right;">R$ <?=$oMoeda->money($TOTAL_CREDITO,"atb");?></td>
          </tr>
		 </tbody>
		 </table></div>
        <div class="well">
<h4>Resultado R$</h4>       
			<table class="table table-striped table-bordered">
			<thead>
			<tr>
			<th>Descrição</th>
			<th>Real</th>
			<th>Dollar</th>
			<th>Câmbio</th>
			</thead>
	        <tbody>
			<tr>
				<th>Receita Bruta</th>
				 <td style="text-align:right;">R$ <?=$oMoeda->money($TOTAL_RECEBIMENTO_REAL,"atb");?></td>
				 <td style="text-align:right;">U$ <?=$oMoeda->money($TOTAL_RECEBIMENTO_DOLLAR,"atb");?></td>
				 <td style="text-align:right;"></td>
          		</tr>
				<tr>
				<th>Custo</th>
				 <td style="text-align:right;">R$ <?=$oMoeda->money($TOTAL_CUSTO_REAL,"atb");?></td>
				 <td style="text-align:right;">U$ <?=$oMoeda->money($TOTAL_CUSTO_DOLLAR,"atb");?></td>
				 <td style="text-align:right;">R$ <?=$oMoeda->money($oGrupo->cotacaoCusto,"atb");?></td>
          		</tr>
				<tr>
				<th>Receita Liquida:</th>
				 <td style="text-align:right;">R$ <?=$oMoeda->money($TOTAL_RECEBIMENTO_REAL - $TOTAL_CUSTO_REAL,"atb");?></td>
				 <td style="text-align:right;">U$ <?=$oMoeda->money($TOTAL_RECEBIMENTO_DOLLAR - $TOTAL_CUSTO_DOLLAR,"atb");?></td>
				 <td style="text-align:right;"></td>
          		</tr>		 	
		 </tbody>
		 </table>
		</div>
</section>
		
		
		
		
	</div>
	<div class="rodape">
	Comunidade Obra de Maria - Rainha Tour e Viagens Ltda-ME - Tel.: (61) 3201-5116 - Site: www.obrademariadf.com.br - Email: brasilia@obrademaria.com.br
    </div>
</div>
<div class="modal hide fade" id="myModalEmail" >
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>Enviar para E-mail</h3>
  </div>
  <div class="modal-body">
    <p id="boxRelatEmail">
 <div class="control-group"> 
    <label for="destinatario" class="control-label">Destinatário:</label>
    <div class="controls"> 
      <input type="text" id="destinatario" class="input-xlarge span3" name="destinatario" value="" placeholder="E-mail destinatário">    
    </div>
  </div>
  <div class="control-group"> 
    <label for="destinatario" class="control-label">Informações:</label>
    <div class="controls"> 
      <input type="text" id="infoEmailRelat" class="input-xlarge span6" name="infoEmailRelat" value="" placeholder="Informações">    
    </div>
  </div>
  <input type="hidden" id="textoEmailRelat" class="input-xlarge span2" name="textoEmailRelat" value="">
    </p>
  </div>
  <div class="modal-footer">
    <a data-toggle="modal" href="#myModalEmail" class="btn">Cancelar</a>
    <a href="#" class="btn btn-primary" id="bt_enviaRelat">Confirmar</a>
  </div>
</div>
</body>
</html>