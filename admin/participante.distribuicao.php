<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 13;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '    <ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="grupos.andamento.php">Grupos</a> <span class="divider">/</span>
    </li>
	<li>
    <a href="participante.lista.php?idGrupo='.$_REQUEST['idGrupo'].'">Participantes</a> <span class="divider">/</span>
    </li>
    <li class="active">Distribuição</li>
    </ul>';
}
//configura o grupo na pagina
$oGrupo = new Grupo();
$idGrupo = $oGrupo->md5_decrypt($_REQUEST['idGrupo']);
$oGrupo->getById($idGrupo);
$tpl->NOME_GRUPO = $oGrupo->nomePacote;
$tpl->ID_GRUPO_HASH = $_REQUEST['idGrupo'];

$oD = new Distribuicao();
$oQuarto = new Quarto();
$oParticipante = new Participante();
$idPartic = $oParticipante->md5_decrypt($_REQUEST['idParticipante']);
$oParticipante->getById($idPartic);
$rsQ = $oQuarto->getRows(0,999,array("id"=>"asc"),array("grupo"=>" = ".$idGrupo));
$tpl->NOME_PARTICIPANTE = $oParticipante->cliente->nomeCompleto;
$tpl->ID_PARTICIPANTE_HASH = $_REQUEST['idParticipante'];
foreach($rsQ as $key => $quarto){
	$tpl->ID_QUARTO = $quarto->id;
	$tpl->ID_QUARTO_HASH = $oQuarto->md5_encrypt($quarto->id);
	$tpl->NUMERO_QUARTO = $quarto->numero;
	$tpl->CAPACIDADE = $quarto->capacidade;
	$rsd = $oD->getRows(0,999,array("id"=>"asc"),array("quarto"=>" = ".$quarto->id));
	$tpl->CLASS_COLLAPSE = 'collapse';
	$tpl->STYLE_COLLAPSE = 'height: 0px;';
		foreach($rsd as $keyD => $distribuicao){
			$tpl->NOME_PART_QUARTO = $distribuicao->participante->cliente->nomeCompleto;
			if($oParticipante->id == $distribuicao->participante->id){
				$on = true;
				$tpl->block("BLOCK_PARTICIPANTE_EXC");
			}else{			
			$tpl->block("BLOCK_PARTICIPANTE");
			}
		}
		if($on){
			$tpl->CLASS_COLLAPSE = 'in';
			$tpl->STYLE_COLLAPSE = '';
		}else{
		$tpl->block('BLOCK_ADD');
		}
	$on = false;
	$tpl->block("BLOCK_QUARTO");
}

include("tupi.template.finalizar.php"); 
?>