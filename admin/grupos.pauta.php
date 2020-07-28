<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 8;
include("tupi.seguranca.php");
if(!isset($_REQUEST['ajax'])){
$tpl->BREADCRUMB = '<ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="#">Cadastro</a> <span class="divider">/</span>
    </li>
    <li class="active">Lista Grupos</li>
    </ul>';
}
//configura o grupo na pagina
$oGrupo = new Grupo();
$oP = new PautaReuniao();
$idGrupo = $oGrupo->md5_decrypt($_REQUEST['idGrupo']);
$oGrupo->getById($idGrupo);
$tpl->NOME_GRUPO = $oGrupo->nomePacote;
$tpl->ID_GRUPO = $_REQUEST['idGrupo'];
$tpl->ACAO = 'SalvaPauta';
$oP->getByGrupo($idGrupo);
if($oP->id != NULL){
$tpl->PAUTA = $oP->pauta;
$tpl->ID = $oP->id;
}
include("tupi.template.finalizar.php"); 
?>