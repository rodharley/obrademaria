<?php
//verificando o controlador e a acao
$arrayController = explode("/",$_SERVER['SCRIPT_NAME']);
$controller = $arrayController[count($arrayController)-1];
$arrayController = explode(".", $controller);
if(isset($_REQUEST['ajax'])){
	header("Content-Type: text/html; charset=iso-8859-1");
	if($arrayController[1] == "php"){
	$tpl = new Template("templates/".$arrayController[0].".html");
	}else{	
	$tpl = new Template("templates/".$arrayController[0].".".$arrayController[1].".html");
	}
	
}else{
	if(isset($codTemplate)){
	$tpl = new Template("templates/".$codTemplate.".html");
	}else{
	$tpl = new Template("templates/default.html");

		$tpl->TITULO = $tupi->TITULO;
		$codAcessoMenu = 15;
		if(isset($_SESSION['ag_itensMenu'])){
		//montagem do menu
		$oMenu = new menu();
		$menus = $oMenu->getMenus();
		$relatGrupo = false;
		$relatGestao = false;
		$realtFinan = false;
			foreach($menus as $key => $menu){
			$tpl->DESCMENU = $menu->descricao;
			if($menu->id == $codAcessoMenu){
			$tpl->CLASSMENU = 'current';
			}else
			$tpl->CLASSMENU = 'select';
			if(count($menu->subMenus) > 0 ){
				foreach($menu->subMenus as $key2 => $submenu){
					//imprime a label dos relatorios do grupo
					if(!$relatGrupo && $submenu->id == 14){
					$tpl->block("BLOCK_SUBMENU_GRUPO");
					$relatGrupo = true;
					}
					
					//imprime a label dos relatorios de gestao
					if(!$relatGestao && $submenu->id == 24){
					$tpl->block("BLOCK_SUBMENU_GESTAO");
					$relatGestao = true;
					}
					
					//imprime a label dos relatorios de financeiro
					if(!$realtFinan && $submenu->id == 34){
					$tpl->block("BLOCK_SUBMENU_FINAN");
					$realtFinan = true;
					}
				
				
				
					$tpl->DESCSUBMENU = $submenu->descricao;
					$tpl->URLSUBMENU = $submenu->url;
				
					if(strpos($_SESSION['ag_itensMenu'],",".$submenu->id) !== false) 
					$tpl->block("BLOCK_SUBMENU");			
					}			
				}
				if(strpos($_SESSION['ag_itensMenu'],",".$menu->id) !== false)
				$tpl->block("BLOCK_MENU");
				$tpl->clear('CLASSMENU');
				
				
			}
		//nomes e perfil de usuari9o
		$tpl->USUARIO = $_SESSION['ag_nomeUsuario'];
		}
		//mostrnado os menus
		if(isset($_SESSION['ag_idUsuario'])){
			$tpl->block("MENU_PADRAO");
			$tpl->block("MENU_DE_NAVEGACAO");
		}
		
	} //fim se o template é padrao
	//montando a navegacao
	if($arrayController[1] == "php"){
	$tpl->addFile("CONTEUDO","templates/".$arrayController[0].".html");
	}else{	
	$tpl->addFile("CONTEUDO","templates/".$arrayController[0].".".$arrayController[1].".html");
	}
}//fim do else nao for requisicao ajax


//carregando o módulo de alertas
if(isset($_SESSION['tupi.mensagem'])){
$oMensagem = new Mensagem();
$oMensagem->getMensagem($_SESSION['tupi.mensagem']);
$tipo = "";
if($oMensagem->tipo != "")
	$tipo = 'alert-'.$oMensagem->tipo;
$tpl->ALERT = '<div class="alert '.$tipo.'"><a class="close" data-dismiss="alert">x</a>'.utf8_decode($oMensagem->mensagem).'</div>';
unset($_SESSION['tupi.mensagem']);
}


?>