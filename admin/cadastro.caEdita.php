<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 46;
include("tupi.seguranca.php");
$tpl->BREADCRUMB = '    <ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="#">Cadastro</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="cadastro.ca.php">Companhias Aéreas</a> <span class="divider">/</span>
    </li>

    <li class="active">Editar Companhia Aérea</li>
    </ul>';
//recuperacao do usuario
$oCA = new CompanhiaAerea();
$tpl->ACAO = "Incluir";
$idPerfil = 0;
if(isset($_REQUEST['idCA'])){
$oCA->getById($oCA->md5_Decrypt($_REQUEST['idCA']));	
$tpl->DESCRICAO = $oCA->descricao;
$tpl->LOGO = $oCA->logomarca;
$tpl->ACAO = "Alterar";
$tpl->ID = $oCA->id;
}

include("tupi.template.finalizar.php"); 
?>