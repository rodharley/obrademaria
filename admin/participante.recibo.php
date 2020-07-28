<?php 
include("tupi.inicializar.php"); 
$codTemplate = "relatorio";
include("tupi.template.inicializar.php"); 
$codAcesso = 13;
include("tupi.seguranca.php");
$pag = new Pagamento();
$cheque = new Cheque();
$moeda = new Moeda();
$pag->getById($pag->md5_decrypt($_REQUEST['idPagamento']));
$cliente = $pag->participante->cliente;
$grupo = $pag->participante->grupo;
$moeda = $pag->moeda;
$oTipoP = new TipoPagamento();
if($cliente->sexo == "F"){
$tpl->ARTIGO = "a";
$tpl->SENHOR = "Sra.";
}else{
$tpl->ARTIGO = "o";
$tpl->SENHOR = "Sr.";
}
$tpl->NUMERO = str_pad($pag->id,6,"0",STR_PAD_LEFT);
$tpl->NOME_COMPLETO = $cliente->nomeCompleto;
$tpl->VALOR = $pag->money($pag->CALCULA_REAL(),"atb");

//$velhos = array("Reais", "REAIS", "REAL", "Real");
//$novos   = array($moeda->plural, strtoupper($moeda->plural), strtoupper($moeda->descricao),$moeda->descricao);

$extenso = $pag->extenso($pag->money($pag->CALCULA_REAL(),"atb"),true);

$tpl->VALOR_EXTENSO = $extenso;
$tpl->REFERENTE =  $pag->obs;
$tpl->FINALIDADE = $pag->finalidade->descricao;
$tpl->NOME_GRUPO = $grupo->nomePacote;
$tpl->VALOR_CAMBIO = $pag->money($pag->cotacaoReal,"atb");
$tpl->FORMA_PAGAMENTO = $pag->tipo->descricao;



if($pag->tipo->id == $oTipoP->CHEQUE()){
$rsc = $cheque->getRows(0,999,array(),array("pagamento"=>" = ".$pag->id));
foreach($rsc as $key => $cheque){
$tpl->EMISSOR = $cheque->emissor->nomeCompleto;
$tpl->BANCO = $pag->banco->nome;
$tpl->NR_CHEQUE = $cheque->numeroCheque;
$tpl->DATA = $pag->convdata($cheque->dataCompensacao,"mtn");
$tpl->VALOR_CHEQUE = $pag->money($cheque->valor,"atb");
$tpl->block("BLOCK_ITEM_LISTA");
}

$tpl->block("BLOCK_CHEQUES");

}

if($pag->tipo->id == $oTipoP->CARTAO()){
$tpl->parcela = $pag->parcela;
if($pag->bandeira != null){
	$tpl->img_bandeira  =$pag->bandeira->imagem;	
	$tpl->bandeira = $pag->bandeira->descricao;
}
$tpl->block("BLOCK_CARTAO");	
}
$tsinscricao = strtotime($pag->dataPagamento);

$tpl->DATA_RECIBO = date("d",$tsinscricao)." de ".$pag->mesExtenso(date("m",$tsinscricao))." de ".date("Y",$tsinscricao);
include("tupi.template.finalizar.php"); 
?>