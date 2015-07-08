<?php 
include("tupi.inicializar.php"); 
$codTemplate = "relatorioHorizontal";
include("tupi.template.inicializar.php"); 
$codAcesso = 37;
include("tupi.seguranca.php");
//titulo do relatorio
$tpl->TITULO = "Relatório de desistentes por período";
$tpl->DATA_RELATORIO = "Data/Hora:" .date("d/m/Y h:i:s");
//declara as classes
$om = new Moeda();
$oP = new Pagamento();
$oTP = new TipoPagamento();
$oTT = new TipoTransferencia();
$oG = new Grupo();
$oPartic = new Participante();
$oF = new FinalidadePagamento();
$oC = new Credito();

$oG->getById($oG->md5_decrypt($_REQUEST['idGrupo']));
$totalRecebDollar = 0;
$totalRecebReal = 0;
$totalDevolucaoDollar = 0;
$totalDevolucaoReal = 0;
$qtdParticipante = 0;

		
	//recupera os participantes
	$rsPartic = $oPartic->getRows(0,999,array(),array("grupo"=>"=".$oG->id,"status"=>"=".$oPartic->STATUS_DESISTENTE()));
	foreach($rsPartic as $keyPart => $part){
		$qtdParticipante++;
		$RecebDollar = 0;
		$RecebReal = 0;
		$CanceladoDollar = 0;
		$CanceladoReal = 0;
		$CreditoDollar = 0;
		$CreditoReal = 0;
		//RECUPERA TODOS OS PAGAMENTOS CANCELADOS DO PARTICIPANTE
		$rsPgt = $oP->getRows(0,999,array(),array("participante" => "=".$part->id, "cancelado"=>"=1"));
		foreach($rsPgt as $keyPag => $p){
			$RecebDollar += $p->CALCULA_DOLLAR();
			$RecebReal += $p->CALCULA_REAL();
		}
			
		
		//recupera os pagamentos de cancelamento do participante
		$rsPgt = $oP->getRows(0,999,array(),array("participante" => "=".$part->id, "cancelado"=>"=0","finalidade"=>"=".$oF->CANCELAMENTO()));
		foreach($rsPgt as $keyPag => $p){
			$CanceladoDollar += $p->CALCULA_DOLLAR();
			$CanceladoReal += $p->CALCULA_REAL();
		}
		
		//recupera o credito deixado para o cliente pelo cancelamento
		$rsCred = $oC->getRows(0,999,array(),array("participante" => "=".$part->id));
		foreach($rsCred as $keyCred => $cred){
			$CreditoDollar += $cred->CALCULA_DOLLAR();
			$CreditoReal += $cred->CALCULA_REAL();
			echo $CreditoDollar."credito dollar";
			echo $CreditoReal."credito real";
		}
		
		$tpl->GRUPO = $oG->nomePacote;
		$tpl->PARTICIPANTE = $part->cliente->nomeCompleto;
		$tpl->RECEBIMENTO_DOLLAR = $oP->money($RecebDollar,"atb");
		$tpl->RECEBIMENTO_REAL = $oP->money($RecebReal,"atb");
		$tpl->DEVOLUCAO_DOLLAR = $oP->money($RecebDollar - ($CanceladoDollar+$CreditoDollar),"atb");
		$tpl->DEVOLUCAO_REAL = $oP->money($RecebReal - ($CanceladoReal+$CreditoReal),"atb");
		$tpl->block("BLOCK_ITEM_LISTA");
		//soma os totalizadores
		
		$totalRecebDollar += $RecebDollar;
		$totalRecebReal += $RecebReal;
		$totalDevolucaoDollar += $RecebDollar - ($CanceladoDollar+$CreditoDollar);
		$totalDevolucaoReal += $RecebReal - ($CanceladoReal+$CreditoReal);
		
	}//fim do loop de participantes
	
$tpl->TOTAL_PARTICIPANTE = $qtdParticipante;
$tpl->TOTAL_R_DOLLAR = $om->money($totalRecebDollar,"atb");
$tpl->TOTAL_R_REAL = $om->money($totalRecebReal,"atb");
$tpl->TOTAL_DEVOLUCAO_DOLLAR = $om->money($totalDevolucaoDollar,"atb");
$tpl->TOTAL_DEVOLUCAO_REAL = $om->money($totalDevolucaoReal,"atb");
//$tpl->DATA_ATUAL = date("d/m/Y");
$tpl->NOME_GRUPO = $oG->nomePacote;
include("tupi.template.finalizar.php"); 
?>