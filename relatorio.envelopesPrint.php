<?php 
include("tupi.inicializar.php"); 
$codTemplate = 'template_envelope';
include("tupi.template.inicializar.php"); 
$codAcesso = 22;
include("tupi.seguranca.php");
//titulo do relatorio
$tpl->TITULO = "Envelopes de Participantes";
$opartic = new Participante();
$rs = $opartic->getRows(0,999,array("id"=>"asc"),array("grupo"=>"=".$opartic->md5_decrypt($_REQUEST['idGrupo']),"status"=>"!=".$opartic->STATUS_DESISTENTE()));
foreach($rs as $key => $p){
$tpl->NOME_GRUPO = $p->grupo->nomePacote;
$tpl->DATA_SAIDA = $p->convdata($p->grupo->dataEmbarque,"mtn");
$tpl->DATA_CHEGADA = $p->convdata($p->grupo->dataChegada,"mtn");
$sobrenome = explode(" ",$p->nomeFamilia());
$tpl->SOBRENOME = $sobrenome[0];
$tpl->NOME = $sobrenome[2];
$tpl->DATA_NASC = $p->convdata($p->cliente->dataNascimento,"mtn");
$tpl->CIDADE_NASC = $p->cliente->cidadeNascimento;
$tpl->FONE1 = $p->cliente->telefoneResidencial; 
$tpl->FONE2 = $p->cliente->telefoneComercial;
$tpl->FONE3 = $p->cliente->celular;
$tpl->PASSAPORTE = $p->cliente->passaporte;
$tpl->EXPEDICAO = $p->convdata($p->cliente->dataEmissaoPassaporte,"mtn");
$tpl->VALIDADE = $p->convdata($p->cliente->dataValidadePassaporte,"mtn");
$tpl->VALOR_TOTAL = $p->grupo->moeda->cifrao." ".$p->money($p->valorTotal,"atb");
$oAbat = new Abatimento();
$rsAbats = $oAbat->getRows(0,999,array(),array("participante"=>"=".$p->id));
foreach($rsAbats as $keyA => $abat){
$tpl->DATA_PAG = $abat->pagamento->dataPagamento;
$tpl->TIPO_PAG = $abat->pagamento->tipo->descricao;
$tpl->VALOR_PAGO_DOLLAR = $p->money($abat->getValorDollar(),"atb");
$tpl->VALOR_PAGO_REAL = $p->money($abat->getValorReal(),"atb");
$tpl->block("BLOCK_ITEM_LISTA");
}
$tpl->block("BLOCK_FOLHA");
}
include("tupi.template.finalizar.php"); 
?>