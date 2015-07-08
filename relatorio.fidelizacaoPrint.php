<?php 
include("tupi.inicializar.php"); 
$codTemplate = "relatorio";
include("tupi.template.inicializar.php"); 
$codAcesso = 26;
include("tupi.seguranca.php");

$oGrupo = new Grupo();
$oCliente = new Cliente();
$rs = $oCliente->fidelizacao(1);
$tpl->DATA_RELATORIO = "Data/Hora:" .date("d/m/Y h:i:s");
foreach($rs as $key => $cliente){
$tpl->NUMERO = $cliente->id;
$tpl->PARTICIPANTE = $cliente->nomeCompleto;
$tpl->TELEFONE = $cliente->telefoneResidencial != "" ? $cliente->telefoneResidencial : $cliente->celular != "" ? $cliente->celular : $cliente->telefoneComercial;
$tpl->EMAIL =  $cliente->email;
$rsGrupo = $oGrupo->gruposDeCliente($cliente->id);
$listaGrupos = "";
$contG = 1;
foreach($rsGrupo as $keyG => $grupo){
$listaGrupos .= $contG."-".$grupo->nomePacote."<br/>";
$contG++;
}
$tpl->GRUPOS = $listaGrupos;
$tpl->block("BLOCK_ITEM_LISTA");
$cont++;
}
include("tupi.template.finalizar.php"); 
?>