<?php 
include("tupi.inicializar.php"); 
$codTemplate = "relatorio";
include("tupi.template.inicializar.php"); 
$codAcesso = 10;
include("tupi.seguranca.php");

//configura o grupo na pagina
$oGrupo = new Grupo();
$oP = new PautaReuniao();
$idGrupo = $oGrupo->md5_decrypt($_REQUEST['idGrupo']);
$oGrupo->getById($idGrupo);
$tpl->NOME_GRUPO = $oGrupo->nomePacote;
$oP->getByGrupo($idGrupo);
if($oP->id != NULL){
$tpl->PAUTA = $oP->pauta;
}
include("tupi.template.finalizar.php"); 
?>