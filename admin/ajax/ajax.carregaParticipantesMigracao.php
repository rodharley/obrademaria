<?
include("../tupi.inicializar.php");
include("../tupi.template.inicializar.php");
$oGrupo = new Grupo();
$idGrupo = $oGrupo->md5_decrypt($_REQUEST['idGrupo']);
$oGrupo->getById($idGrupo);

$oParticipante = new Participante();
$arrayfiltro  = array("grupo"=>" = '".$idGrupo."'");
$arrayorderm  = array("id"=>"ASC");
$participantes = $oParticipante->getRows(0,999,$arrayorderm,$arrayfiltro);
foreach($participantes as $key => $participante){
	$tpl->CIFRAO = $oGrupo->moeda->cifrao;
	$tpl->PAGO = $oParticipante->money($participante->recuperaValorPago(),"atb")."/".$oParticipante->money($participante->valorTotal,"atb");
	$tpl->RESTA = "Pendêndia: ".$oGrupo->moeda->cifrao." ".$oParticipante->money(($participante->valorTotal-$participante->recuperaValorPago()),"atb");
	$tpl->NOME = $participante->cliente->nomeCompleto;
	$tpl->OPCIONAL = $participante->pacoteOpcional ? "Sim" : "Não";
	//$tpl->ID_CLIENTE_HASH = $oParticipante->md5_encrypt($participante->cliente->id);
	$tpl->CPF = $oParticipante->formataCPFCNPJ($participante->cliente->cpf);
	$tpl->SITUACAO = $participante->status->descricao;
	$tpl->ID = $participante->id;

	$tpl->block('BLOCK_ITEM_LISTA');
}
include("../tupi.template.finalizar.php");
?>