<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 7;
include("tupi.seguranca.php");
$tpl->BREADCRUMB = '    <ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="#">Cadastro</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="cadastro.usuarios.php">Usuários</a> <span class="divider">/</span>
    </li>

    <li class="active">Editar Usuário</li>
    </ul>';
//recuperacao do usuario
$oUsuario = new Usuario();
$tpl->ACAO = "Incluir";
$idPerfil = 0;
if(isset($_REQUEST['idUsuario'])){
$oUsuario->getById($oUsuario->md5_Decrypt($_REQUEST['idUsuario']));	
$tpl->NOME = $oUsuario->nome;
$tpl->EMAIL = $oUsuario->email;
$tpl->SENHA = $oUsuario->senha;
$idPerfil = $oUsuario->perfil->id;
$tpl->ACAO = "Alterar";
$tpl->ID = $oUsuario->id;
}


//MONTAGEM DOS ACESSO DE MENU
$oPerfil = new Perfil();
$rsPerfis = $oPerfil->getRows();

	foreach($rsPerfis as $key => $perfil){
	$tpl->LABEL_PERFIL = $perfil->descricao;
	$tpl->ID_PERFIL = $perfil->id;	
	if($perfil->id == $idPerfil)
	$tpl->CHECKED_PERFIL = "selected";
	
	$tpl->block("BLOCK_COMBO_PERFIL");
	$tpl->clear("CHECKED_PERFIL");
	
	}




include("tupi.template.finalizar.php"); 
?>