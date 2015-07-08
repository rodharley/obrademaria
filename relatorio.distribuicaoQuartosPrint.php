<?php 
include("tupi.inicializar.php"); 
$codTemplate = "relatorio";
include("tupi.template.inicializar.php"); 
$codAcesso = 15;
include("tupi.seguranca.php");
$ogrupo = new Grupo();
$ogrupo->getById($ogrupo->md5_decrypt($_REQUEST['idGrupo']));
$tpl->COD_GRUPO = str_pad($ogrupo->id,7,"0", STR_PAD_LEFT);
$tpl->NOME_GRUPO = $ogrupo->nomePacote;
$tpl->ID_GRUPO_HASH = $_REQUEST['idGrupo'];
$tpl->DATA_RELATORIO = "Data/Hora:" .date("d/m/Y h:i:s");
$oD = new Distribuicao();
$oQuarto = new Quarto();
$oParticipante = new Participante();
$rsQ = $oQuarto->getRows(0,999,array("capacidade"=>"asc","numero"=>"asc"),array("grupo"=>" = ".$ogrupo->id));
foreach($rsQ as $key => $quarto){
	$tpl->N_QUARTO = $quarto->numero;
	//particpantes no quarto
	$rsd = $oD->getRows(0,999,array("id"=>"asc"),array("quarto"=>" = ".$quarto->id));
	foreach($rsd as $keyD => $distribuicao){		
		$tpl->OBS = "";
		$tpl->LASTNAME = $distribuicao->participante->nomeFamilia();			
		$tpl->IDADE = $distribuicao->participante->cliente->idade();	
		$tpl->ACESSO = $distribuicao->participante->cliente->preferencial == 1 ? "Sim " : "Não";
		if($distribuicao->participante->cliente->problemasSaude != "")
		$tpl->OBS = "Problemas de saúde:<br/>".$distribuicao->participante->cliente->problemasSaude;
		if($distribuicao->participante->cliente->restricaoAlimentar != "")
		$tpl->OBS .= "<BR>Restrição Alimentar:<br>".$distribuicao->participante->cliente->restricaoAlimentar;
		$tpl->block("BLOCK_SUBITEM_LISTA");
	}
	$tpl->block("BLOCK_ITEM_LISTA");
}
if(!isset($_REQUEST['tupiSendEmail']))
$tpl->block("BLOCK_ENVIO_EMAIL");
include("tupi.template.finalizar.php"); 
?>