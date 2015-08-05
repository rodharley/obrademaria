<?php 
include("tupi.inicializar.php"); 
$codTemplate = "relatorio";
include("tupi.template.inicializar.php"); 
$codAcesso = 21;
include("tupi.seguranca.php");
//titulo do relatorio
$tpl->TITULO = "Relatório de Cartões de Crédito";
$tpl->DATA_RELATORIO = "Data/Hora:" .date("d/m/Y h:i:s");
$ogrupo = new Grupo();
$om = new Moeda();
$oAbat = new Abatimento();
$ogrupo->getById($ogrupo->md5_decrypt($_REQUEST['idGrupo']));
$tpl->COD_GRUPO = str_pad($ogrupo->id,7,"0", STR_PAD_LEFT);
$tpl->NOME_GRUPO = $ogrupo->nomePacote;
$tpl->VALOR_GRUPO = $ogrupo->money($ogrupo->getValorTotal(0),"atb");
$tpl->CIFRAO_GRUPO = $ogrupo->moeda->cifrao;
$tpl->MOEDA_GRUPO_PLURAL = $ogrupo->moeda->plural;
//pacote opcional
if($ogrupo->possuiPacoteOpcional == 1){
$tpl->NOME_GRUPO_OPCIONAL = $ogrupo->nomePacoteOpcional;
$tpl->VALOR_GRUPO_OPCIONAL = $ogrupo->money($ogrupo->getValorTotalOpcional(),"atb");
$tpl->block("BLOCK_OPCIONAL");
}

//recupera pagamentos cartoes
$oPag = new Pagamento;
$oTipoPag = new TipoPagamento;
$rs = $oPag->getPagamentos($ogrupo->id,$oTipoPag->CARTAO());
$totalGeralPagoReal = 0;
foreach($rs as $key => $p){
$totalReal = 0;
$tpl->CODIGO = $p->codAutorizacao;
$tpl->NUMERO_CARTAO = $p->numeroCartao;
$tpl->VALOR_PARCELA = $oPag->money($p->valorParcela,"atb");
$tpl->NOME_PARTICIPANTE = $p->participante->cliente->nomeCompleto;
$tpl->VALOR_PAGO_REAL = $oPag->money($p->valorPagamento,"atb");
$tpl->DATA = $oPag->convdata($p->dataPagamento,"mtn");
$totalGeralPagoReal += $p->valorPagamento;
$tpl->block("BLOCK_ITEM_LISTA");

}
$tpl->TOTAL_VALOR_PAGO_REAL = $om->money($totalGeralPagoReal,"atb");
include("tupi.template.finalizar.php"); 
?>