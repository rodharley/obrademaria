<?php
include("tupi.inicializar.php");
include("tupi.template.inicializar.php");
$codAcesso = 11;
include("tupi.seguranca.php");
$tpl->BREADCRUMB = '    <ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="grupos.andamento.php">Grupos</a> <span class="divider">/</span>
    </li>
	 <li>
    <a href="quarto.lista.php?idGrupo='.$_REQUEST['idGrupo'].'">Quarto</a> <span class="divider">/</span>
    </li>
    <li class="active">Edita Quarto</li>
    </ul>';

//configura o grupo na pagina
$oGrupo = new Grupo();
$idGrupo = $oGrupo->md5_decrypt($_REQUEST['idGrupo']);
$oGrupo->getById($idGrupo);
$tpl->NOME_GRUPO = $oGrupo->nomePacote;


$oQuarto = new Quarto();
$tpl->ACAO = "Incluir";
$tpl->ID_GRUPO = $_REQUEST['idGrupo'];
if(isset($_REQUEST['idQuarto'])){
$oQuarto->getById($oQuarto->md5_Decrypt($_REQUEST['idQuarto']));

$tpl->NUMERO = $oQuarto->numero;
$tpl->CAPACIDADE = $oQuarto->capacidade;
$tpl->ACAO = "Alterar";
$tpl->ID = $oQuarto->id;
}

include("tupi.template.finalizar.php");
?>