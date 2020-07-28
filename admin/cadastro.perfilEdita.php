<?php 
include("tupi.inicializar.php"); 
include("tupi.template.inicializar.php"); 
$codAcesso = 6;
include("tupi.seguranca.php");
$tpl->BREADCRUMB = '    <ul class="breadcrumb">
    <li>
    <a href="home.php">Home</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="#">Cadastro</a> <span class="divider">/</span>
    </li>
    <li>
    <a href="cadastro.perfis.php">Perfis</a> <span class="divider">/</span>
    </li>

    <li class="active">Editar Perfil</li>
    </ul>';
//recuperacao do perfil
$oPerfil = new Perfil();
$tpl->ACAO = "Incluir";
$listMenu = array();
if(isset($_REQUEST['idPerfil'])){
$oPerfil->getById($oPerfil->md5_Decrypt($_REQUEST['idPerfil']));	
$tpl->DESCRICAO = $oPerfil->descricao;
$tpl->ACAO = "Alterar";
$tpl->ID = $oPerfil->id;
$oAcesso = new acesso();
$acessos = $oAcesso->getRows(0,999,array(),array("perfil" => " = ".$oPerfil->id));
foreach ($acessos as $acesso){
array_push($listMenu,$acesso->menu->id);	
}
}


//MONTAGEM DOS ACESSO DE MENU
$oMenu = new Menu();
$menus = $oMenu->getMenus();

	foreach($menus as $key => $menu){
	$tpl->DESC_MENU = $menu->descricao;
	$tpl->ID_MENU = $menu->id;
	if(count($menu->subMenus) > 0 ){
		foreach($menu->subMenus as $key2 => $submenu){
			$tpl->DESC_SUBMENU = $submenu->descricao;
			$tpl->ID_SUBMENU = $submenu->id;
			if(array_search($submenu->id,$listMenu) !== false) 
				$tpl->CHECKED_SUBMENU = 'checked="checked"';
			
			$tpl->block("BLOCK_SUBMENU_PERFIL");
			$tpl->clear("CHECKED_SUBMENU");
			
		}
		if(array_search($menu->id,$listMenu) !== false) 
			$tpl->CHECKED_MENU = 'checked="checked"';
		$tpl->block("BLOCK_MENU_PERFIL");
		$tpl->clear("CHECKED_MENU");
	}
	}



include("tupi.template.finalizar.php"); 
?>